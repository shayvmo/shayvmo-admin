<?php


namespace App\Http\Controllers\Admin;


use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DebtLogListRequest;
use App\Http\Requests\Backend\DebtLogPostRequest;
use App\Models\DebtDetailLog;
use App\Models\DebtLog;
use App\Services\Base\CommonService;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function center()
    {
        $data = DebtDetailLog::query()->select([
            'id',
            'total_money',
            'plan_back_date',
        ])->where([
            'status' => 0,
        ])->orderBy('plan_back_date')->get();
        $data = collect($data)->groupBy(function ($item, $key) {
            return substr($item['plan_back_date'], 0, 7);
        });
        $data->transform(function ($item, $key) {
            return round($item->sum('total_money'), 2);
        });
        $cakeData = $data->map(function ($item, $key) {
            return [
                'name' => $key,
                'value' => $item,
            ];
        })->values()->toArray();
        $months = $data->keys()->toArray();
        $values = $data->values()->toArray();
        return view('admin.debt.center', compact('months', 'values', 'cakeData'));
    }

    public function getMonthDebt(Request $request)
    {
        $month = $request->get('month') ?? date('Y-m');
        $data = DebtDetailLog::query()->with([
            'debtLog' => function ($query) {
                $query->select([
                    'id',
                    'debt_type',
                    'title',
                    'from_user',
                    'to_user',
                ]);
            }
        ])->select([
            'id',
            'debt_id',
            'money',
            'interest_money',
            'total_money',
            'plan_back_date',
            'status',
            'remark',
        ])->where([
            'status' => 0,
        ])->whereBetween('plan_back_date', [$month . '-01', $month . '-30'])->orderBy('plan_back_date')->get()->toArray();
        return $this->successData($data);
    }

    public function showPage()
    {
        return view('admin.debt.index');
    }

    public function index(DebtLogListRequest $request)
    {
        [
            'page_size' => $page_size,
            'title' => $title,
        ] = $request->fillData();
        $data = DebtLog::query()
            ->with([
                'details' => function ($query) {
                    return $query->whereStatus(0);
                }
            ])
            ->when($title, function ($query, $title) {
                return $query->where('title', 'like', "%$title%");
            })->orderBy('id', 'DESC')->paginate($page_size)->toArray();
        $data = CommonService::changePageDataFormat($data);
        foreach ($data['data'] as &$item) {
            $item['nopay_detail_count'] = count($item['details'] ?: []);
            unset($item['details']);
        }
        return $this->successData($data);
    }

    public function detailPage()
    {
        return view('admin.debt.detail');
    }

    public function detail(DebtLog $debtLog)
    {
        $debtLog->debt_type = (string)$debtLog->debt_type;
        $debtLog->status = (string)$debtLog->status;
        $debtLog->is_installment = (string)$debtLog->is_installment;
        $debtLog->details;
        $debtLog = $debtLog->toArray();
        return $this->successData($debtLog);
    }

    public function store(DebtLogPostRequest $request)
    {
        $data = $request->fillData();
        if ($data['is_installment']) {
            $data['interest_money'] = array_sum(array_column($data['details'], 'interest_money'));
            $data['money'] = array_sum(array_column($data['details'], 'money'));
            $data['installment_num'] = count($data['details']);
        } else {
            $data['installment_num'] = 1;
        }
        $data['total_money'] = bcadd($data['money'], $data['interest_money'] ?? 0, 2);
        $model = DebtLog::create($data);
        if (!$model) {
            return $this->error('添加失败');
        }
        $installment_status = 1;
        array_walk($data['details'], function (&$item) use (&$installment_status) {
            if ($installment_status === 1 && (int)$item['status'] === 0) {
                $installment_status = 0;
            }
            $item['remark'] = $item['remark']?: '';
        });
        $installment_status && $model->status = $installment_status;
        $model->save();
        $data['is_installment'] && $model->details()->createMany($data['details']);
        return $this->success();
    }

    public function update(DebtLog $debtLog, DebtLogPostRequest $request)
    {
        $data = $request->fillData();
        if ($data['is_installment']) {
            $data['interest_money'] = array_sum(array_column($data['details'], 'interest_money'));
            $data['money'] = array_sum(array_column($data['details'], 'money'));
            $data['installment_num'] = count($data['details']);
        } else {
            $data['installment_num'] = 1;
        }
        $data['total_money'] = bcadd($data['money'], $data['interest_money'] ?? 0, 2);
        $debtLog->fill($data)->save();
        $debtLog->details()->delete();
        array_walk($data['details'], function (&$item) {
            $item['remark'] = $item['remark']?: '';
        });
        $data['is_installment'] && $debtLog->details()->createMany($data['details']);
        return $this->success();
    }

    public function destroy(DebtLog $debtLog)
    {
        $debtLog->delete();
        return $this->success();
    }

    /**
     * 更改分期明细状态
     * @param DebtDetailLog $debtDetailLog
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeInstallmentDone(DebtDetailLog $debtDetailLog)
    {
        $debtDetailLog->status = (int)!$debtDetailLog->status;
        $debtDetailLog->saveOrFail();
        $brothers = $debtDetailLog->brothers()->where(['status' => 0])->get()->toArray();
        if (!$brothers) {
            $debtDetailLog->debtLog->status = 1;
        } else {
            $debtDetailLog->debtLog->status = 0;
        }
        $debtDetailLog->debtLog->save();
        return $this->successData($brothers);
    }

    /**
     * 批量删除记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ServiceException
     */
    public function batchDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            throw new ServiceException('请选择需要删除的项');
        }
        DebtLog::destroy($ids);
        return $this->success();
    }
}
