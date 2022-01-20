<?php


namespace App\Http\Controllers\Admin;


use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CapitalLogListRequest;
use App\Http\Requests\Backend\CapitalLogPostRequest;
use App\Models\CapitalLog;
use App\Models\CapitalLogType;
use App\Services\Base\CommonService;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    public function center()
    {
        return view('admin.capital.center');
    }

    public function centerData(Request $request)
    {
        [
            $year, $month
        ] = explode('-', $request->get('month') ?: date('Y-m'));
        $data = CapitalLog::query()->with('logCategory')->select([
            'log_cate',
            \DB::raw('sum(money) AS money'),
        ])->where([
            'log_type' => 1,
            'year' => $year,
            'month' => $month,
        ])->groupBy('log_cate')->get();
        $this_month_total = round($data->sum('money'), 2);
        $month_outgoing_cate = $data->map(function ($item, $key) {
            return [
                'name' => $item['logCategory']['title'],
                'value' => $item['money'],
            ];
        })->all();

        $outgoing_cate = [$month_outgoing_cate];
        $months = [];
        foreach (range(1, 12) as $item) {
            $months[] = $year . '-' . sprintf("%02d", $item);
        }

        $all_data = CapitalLog::query()->select([
            'log_type',
            'month',
            \DB::raw('sum(money) AS money'),
        ])->where(['year' => $year])->groupBy('month')->groupBy('log_type')->get()->toArray();

        $all_data = collect($all_data)->groupBy('log_type')->all();
        $income_data = isset($all_data[2]) ? $all_data[2]->pluck('money', 'month')->all() : [];
        $outgoing_data = isset($all_data[1]) ? $all_data[1]->pluck('money', 'month')->all() : [];

        $this_month_outgoing_data = CapitalLog::query()->select([
            'happened_at',
            \DB::raw('sum(money) AS money'),
        ])->where(['year' => $year, 'month' => $month, 'log_type' => 1])->groupBy('happened_at')->pluck('money', 'happened_at')->toArray();

        foreach ($months as $item) {
            $temp_key = (int)substr($item, 5);
            !isset($income_data[$temp_key]) && $income_data[$temp_key] = 0;
            !isset($outgoing_data[$temp_key]) && $outgoing_data[$temp_key] = 0;
        }

        $days = [];
        foreach (range(1, date('t', strtotime($year.'-'.$month))) as $item) {
            $format_date = $year . '-' . $month . '-'. sprintf('%02d', $item);
            $days[] = $format_date;
            !isset($this_month_outgoing_data[$format_date]) && $this_month_outgoing_data[$format_date] = 0;
        }

        ksort($income_data);
        ksort($outgoing_data);
        ksort($this_month_outgoing_data);
        $income_data = array_values($income_data);
        $outgoing_data = array_values($outgoing_data);
        $this_month_outgoing_data = array_values($this_month_outgoing_data);
        return $this->successData(
            compact(
                'outgoing_cate',
                'months',
                'income_data',
                'outgoing_data',
                'days',
                'this_month_total',
                'this_month_outgoing_data'
            )
        );
    }


    public function showPage()
    {
        $log_type = [
            [
                'id' => 1,
                'name' => '支出',
            ],
            [
                'id' => 2,
                'name' => '收入',
            ],
        ];
        $all_log_cate = CapitalLogType::query()->select(['id', 'type_cate', 'title'])->get()->toArray();
        $all_log_cate = collect($all_log_cate)->groupBy('type_cate')->all();
        $income_log_cate = isset($all_log_cate[2]) ? $all_log_cate[2]->pluck('title', 'id')->all() : [];
        $outgoing_log_cate = isset($all_log_cate[1]) ?$all_log_cate[1]->pluck('title', 'id')->all() : [];
        return view('admin.capital.index', compact('log_type','income_log_cate', 'outgoing_log_cate'));
    }

    public function index(CapitalLogListRequest $request)
    {
        [
            'page_size' => $page_size,
            'log_type' => $log_type,
            'log_cate' => $log_cate,
            'date' => $date,
            'sort_type' => $sort_type,
        ] = $request->fillData();
        $query = CapitalLog::query()
            ->with('logCategory')
            ->when($log_type, function ($query, $log_type) {
                return $query->where('log_type', $log_type);
            })
            ->when($log_cate, function ($query, $log_cate) {
                return $query->where('log_cate', $log_cate);
            })
            ->when($date, function ($query, $date) {
                return $query
                    ->whereBetween('happened_at', $date);
            });

        // 按添加时间排序
        if ($sort_type === 1) {
            $query->orderBy('id', 'DESC');
        }

        // 按发生日期排序
        if ($sort_type === 2) {
            $query->orderBy('happened_at', 'DESC');
        }

        $data = $query->paginate($page_size)->toArray();
        $data = CommonService::changePageDataFormat($data);
        return $this->successData($data);
    }

    public function store(CapitalLogPostRequest $request)
    {
        $data = $request->fillData();
        $year_month_day = explode('-', $data['happened_at']);
        $data['year'] = $year_month_day[0];
        $data['month'] = $year_month_day[1];
        if (!is_numeric($data['log_cate'])) {
            $capital_cate = CapitalLogType::create([
                'type_cate' => $data['log_type'],
                'title' => $data['log_cate'],
            ]);
            $data['log_cate'] = $capital_cate->id;
        }
        $log = CapitalLog::create($data);
        if (!$log->id) {
            return $this->error();
        }
        return $this->success();
    }

    public function update(CapitalLog $capitalLog, CapitalLogPostRequest $request)
    {
        $capitalLog->fill($request->fillData());
        if (!$capitalLog->save()) {
            return $this->error('保存失败');
        }
        return $this->success();
    }

    public function destroy(CapitalLog $capitalLog)
    {
        $capitalLog->delete();
        return $this->success();
    }

    public function batchDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            throw new ServiceException('请选择需要删除的项');
        }
        CapitalLog::destroy($ids);
        return $this->successData($ids);
    }
}
