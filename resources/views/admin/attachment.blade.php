<style scoped>
    .right-side {
        margin: 15px;
    }

    .app-attachment-upload {
        box-shadow: none;
        border: 1px dashed #b2b6bd;
        width: 100px;
        margin: 18px 24px 0;
    }

    .app-attachment-upload i {
        font-size: 30px;
        color: #909399;
    }

    .app-attachment-list {
        display: flex;
        height: 150px;
    }

    .app-attachment-item {
        position: relative;
        top: 17px;
        text-align: center;
        margin-bottom: 15px;
        display: flow-root;
    }

    .app-attachment-item.checked,
    .app-attachment-item.selected {
        box-shadow: 0 0 0 1px #1ed0ff;
        background: #daf5ff;
        border-radius: 5px;
    }

    .app-attachment-dialog .app-attachment-name {
        color: #666666;
        font-size: 13px;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }

    .app-attachment-simple-upload {
        width: 100% !important;
        height: 120px;
        border: 1px dashed #e3e3e3;
        cursor: pointer;
    }

    .app-attachment-simple-upload:hover {
        background: rgba(0, 0, 0, .05);
    }

    .app-attachment-simple-upload i {
        font-size: 32px;
    }

    .el-menu-item.is-active {
        color: #409EFF;
    }
</style>
<template id="app-attachment">
    <div>
        <el-dialog class="app-attachment-dialog"
                   :title="title ? title : '选择文件'"
                   :visible.sync="dialogVisible"
                   @opened="dialogOpened"
                   :close-on-click-modal="false"
                   :width="simple?'20%':'70%'"
                   top="3vh"
                   append-to-body>
            <template v-if="simple">
                <app-upload
                    class="app-attachment-simple-upload"
                    v-loading="uploading"
                    :disabled="uploading"
                    @start="handleStart"
                    @success="handleSuccess"
                    @complete="handleComplete"
                    :multiple="true"
                    :max="10"
                    :params="uploadParams"
                    :fields="uploadFields"
                    :accept="accept"
                >
                    <div v-if="uploading">@{{uploadCompleteFilesNum}}/@{{uploadFilesNum}}</div>
                    <i v-else class="el-icon-upload"></i>
                </app-upload>
            </template>
            <template v-else>
                <el-card>
                    <el-row>
                        <el-col :span="6">
                            <div class="left-side">
                                <el-button size="small" type="primary" @click="showAddGroup(-1)">
                                    添加分组
                                </el-button>
                                <el-input style="width:90%; margin: 15px 0;" size="small" v-model="keyword"
                                          placeholder="请输入分类名称搜索"></el-input>
                                <el-menu class="group-menu"
                                         mode="vertical"
                                         v-loading="groupListLoading">
                                    <el-menu-item index="all" @click="switchGroup(-1)">
                                        <i class="el-icon-tickets"></i>
                                        <span>全部</span>
                                    </el-menu-item>
                                    <template v-for="(item, index) in groupItem">
                                        <el-menu-item :index="'' + index" @click="switchGroup(index)">
                                            <div style="display: flex; justify-content: space-between">
                                                <div style="overflow: hidden;text-overflow: ellipsis">
                                                    <i class="el-icon-tickets"></i>
                                                    <span>@{{item.title}}</span>
                                                </div>
                                                <div style="display: flex">
                                                    <el-button type="text" @click.stop="showAddGroup(index)">编辑</el-button>
                                                    <div style="color:#409EFF;margin:0 2px">|</div>
                                                    <el-button type="text" @click.stop="deleteGroup(index)">删除</el-button>
                                                </div>
                                            </div>
                                        </el-menu-item>
                                    </template>
                                </el-menu>
                            </div>

                        </el-col>
                        <el-col :span="18">
                            <div v-loading="loading" class="right-side">
                                <div class="search" style="margin-right: 12px; display: flex;">
                                    <el-input placeholder="请输入名称搜索" v-model="p_keyword"
                                              clearable
                                              @keyup.enter.native="picSearch"
                                              class="input-with-select"
                                              size="small"
                                              style="margin-right: 10px; width: 300px"
                                    >
                                    </el-input>
                                    <el-button size="small" type="primary" @click="picSearch">查询</el-button>


                                    <div style="margin-left: 20px;">
                                        <el-button v-if="!showEditBlock" size="small" @click="showEditBlock=true">开启编辑</el-button>
                                        <template v-if="showEditBlock">
                                            <el-button size="small" @click="showEditBlock=false" style="margin-right: 12px">退出编辑模式</el-button>
                                            <el-checkbox border size="small" v-model="selectAll"
                                                         @change="selectAllChange"
                                                         label="全选"
                                                         style="margin-right: 12px;margin-bottom: 0"></el-checkbox>
                                            <el-button size="small" :loading="deleteLoading"
                                                       @click="deleteItems"
                                                       style="margin-right: 12px">删除
                                            </el-button>
                                            <el-dropdown size="small" v-loading="moveLoading"
                                                         trigger="click"
                                                         :split-button="true"
                                                         @command="moveItems">
                                                <span>移动至</span>
                                                <el-dropdown-menu slot="dropdown">
                                                    <el-dropdown-item v-for="(item, index) in groupList"
                                                                      :command="index"
                                                                      :key="index">
                                                        @{{item.title}}
                                                    </el-dropdown-item>
                                                </el-dropdown-menu>
                                            </el-dropdown>
                                        </template>
                                    </div>

                                </div>



                                <el-row :gutter="20">
                                    <el-col :span="4">
                                        <div class=" app-attachment-upload">
                                            <app-upload
                                                v-loading="uploading"
                                                :disabled="uploading"
                                                @start="handleStart"
                                                @success="handleSuccess"
                                                @complete="handleComplete"
                                                :multiple="true"
                                                :max="10"
                                                :params="uploadParams"
                                                :fields="uploadFields"
                                                :accept="accept"
                                                flex="main:center cross:center"
                                                style="width: 100px;height: 100px">
                                                <div v-if="uploading">@{{uploadCompleteFilesNum}}/@{{uploadFilesNum}}</div>
                                                <i v-else class="el-icon-upload"></i>
                                            </app-upload>
                                        </div>
                                    </el-col>
                                    <el-col :span="4" v-for="(item, index) in attachments" :key="index" >
                                        <el-tooltip class="item" effect="dark" :content="item.name" placement="top"
                                                    :open-delay="1">
                                            <div
                                                :key="index"
                                                :class="'app-attachment-item'+((item.checked&&!showEditBlock)?' checked':'')+(item.selected&&showEditBlock?' selected':'')"
                                                @click="handleClick(item)">
                                                <img class="app-attachment-img" :src="item.path"
                                                     style="width: 100px;height: 100px;">
                                                <div v-if="item.type == 2" class="app-attachment-img"
                                                     style="width: 100px;height: 100px;position: relative">
                                                    <div v-if="item.cover_pic_src"
                                                         class="app-attachment-video-cover"
                                                         :style="'background-image: url('+item.cover_pic_src+');'"></div>
                                                    <video style="width: 0;height: 0;visibility: hidden;"
                                                           :id="'app_attachment_'+ _uid + '_' + index">
                                                        <source :src="item.path">
                                                    </video>
                                                    <div class="app-attachment-video-info">
                                                        <i class="el-icon-video-play"></i>
                                                        <span>@{{item.duration?item.duration:'--:--'}}</span>
                                                    </div>
                                                </div>
                                                <div v-if="item.type == 3" class="app-attachment-img"
                                                     style="width: 100px;height: 100px;line-height: 100px;text-align: center">
                                                    <i class="file-type-icon el-icon-document"></i>
                                                </div>
                                                <div class="app-attachment-name">@{{item.name}}</div>
                                                <i v-if="false" class="app-attachment-active-icon el-icon-circle-check"></i>
                                            </div>
                                        </el-tooltip>
                                    </el-col>
                                </el-row>
                                <div style="padding: 5px;text-align: right;margin-top: 20px">
                                    <el-pagination
                                        v-if="pagination"
                                        background
                                        @size-change="handleLoadMore"
                                        @current-change="handleLoadMore"
                                        :current-page.sync="page"
                                        :page-size="pagination.pageSize"
                                        layout="prev, pager, next, jumper"
                                        :total="pagination.totalCount">
                                    </el-pagination>
                                </div>
                            </div>

                        </el-col>
                    </el-row>



                <div style="text-align: right">
                    <span v-if="showEditBlock" style="color: #909399">请先退出编辑模式</span>
                    <el-button @click="confirm" type="primary" :disabled="showEditBlock">选定</el-button>
                </div>
                </el-card>
            </template>
        </el-dialog>
        <el-dialog append-to-body title="分组管理" :visible.sync="addGroupVisible" :close-on-click-modal="false"
                   width="25%">
            <el-form @submit.native.prevent label-width="80px" ref="groupForm" :model="groupForm"
                     :rules="groupFormRule">
                <el-form-item label="分组名称" prop="name" style="margin-bottom: 22px;">
                    <el-input v-model="groupForm.name" maxlength="8" show-word-limit></el-input>
                </el-form-item>
                <el-form-item style="text-align: right">
                    <el-button type="primary" @click="groupFormSubmit('groupForm')" :loading="groupFormLoading">保存
                    </el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
        <div style="line-height: normal;" @click="dialogVisible = !dialogVisible"
             :style="'display:'+(display?display:'inline-block')">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    Vue.component('app-attachment', {
        template: '#app-attachment',
        props: {
            display: String,
            title: String,
            multiple: Boolean,
            max: Number,
            params: Object,
            simple: {
                type: Boolean,
                value: false,
            },
            type: {
                type: String,
                default: 'image',
            },
            value: {
                type: String,
                default: '',
            },
            openDialog: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            accept: {
                get() {
                    if (this.type === 'image') {
                        return 'image/*';
                    }
                    if (this.type === 'video') {
                        return 'video/*';
                    }
                    return '*/*';
                },
            },
        },
        watch: {
            openDialog(newVal, oldVal) {
                this.dialogVisible = newVal;
            },
            dialogVisible(newVal, oldVal) {
                if (!newVal) {
                    this.$emit("closed");
                }
            },
            keyword(newVal, oldVal) {
                const groupList = this.groupList;
                let arr = [];
                groupList.map(v => {
                    if (v.title.indexOf(newVal) !== -1) {
                        arr.push(v);
                    }
                });
                this.groupItem = arr;
            }
        },
        data() {
            return {
                canvas: null,
                uploading: false,
                dialogVisible: false,
                loading: true,
                loadingMore: false,
                noMore: false,
                attachments: [],
                checkedAttachments: [],
                uploadParams: {},
                uploadFields: {},
                uploadCompleteFilesNum: 0,
                uploadFilesNum: 0,
                page: 1,
                addGroupVisible: false,
                noMall: true,
                groupList: [],
                groupItem: [],
                groupListLoading: false,
                groupForm: {
                    id: null,
                    name: '',
                },
                groupFormRule: {
                    name: [
                        {required: true, message: '请填写分组名称。',}
                    ],
                },
                groupFormLoading: false,
                showEditBlock: false,
                selectAll: false,
                deleteLoading: false,
                moveLoading: false,
                currentAttachmentGroupId: null,
                video: null,
                keyword: '',
                pagination: null,
                p_keyword: '',
            };
        },
        created() {
        },
        methods: {
            picSearch() {
                this.page = 1;
                this.loading = true;
                this.loadGroups();
                this.loadList();
            },

            dialogOpened() {
                if (this.simple) {
                    return;
                }
                if (!this.attachments || !this.attachments.length) {
                    this.loading = true;
                    this.loadGroups();
                    this.loadList();
                }
            },
            deleteItems() {
                const itemIds = [];
                for (let i in this.attachments) {
                    if (this.attachments[i].selected) {
                        itemIds.push(this.attachments[i].id);
                    }
                }
                if (!itemIds.length) {
                    this.$message.warning('请先选择需要删除的图片。');
                    return;
                }
                let DELETE_URL = '{{route('api.attachment.destroy')}}';
                this.$confirm('确认删除所选的' + itemIds.length + '张图片？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.deleteLoading = true;
                    this.$request({
                        url: DELETE_URL,
                        method: 'post',
                        data: {
                            ids: itemIds,
                        },
                    }).then(e => {
                        this.deleteLoading = false;
                        if (e.data.code === 200) {
                            this.$message.success(e.data.msg);
                            for (let i in itemIds) {
                                for (let j in this.attachments) {
                                    if (this.attachments[j].id == itemIds[i]) {
                                        this.attachments.splice(j, 1);
                                        break;
                                    }
                                }
                            }
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.deleteLoading = false;
                    });
                }).catch(() => {
                });
            },
            selectAllChange(value) {
                for (let i in this.attachments) {
                    this.attachments[i].selected = value;
                }
            },
            selectItem(item) {
                item.selected = item.selected ? false : true;
            },
            moveItems(index) {
                console.log('el-tooltip item app-attachment-item selected');
                let MOVE_URL = '{{route('api.attachment.moveToGroup')}}';
                const itemIds = [];
                for (let i in this.attachments) {
                    if (this.attachments[i].selected) {
                        itemIds.push(this.attachments[i].id);
                    }
                }
                if (!itemIds.length) {
                    this.$message.warning('请先选择需要移动的图片。');
                    return;
                }
                this.$confirm('确认移动所选的' + itemIds.length + '张图片？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.moveLoading = true;
                    this.$request({
                        url: MOVE_URL,
                        method: 'post',
                        data: {
                            ids: itemIds,
                            attachment_group_id: this.groupItem[index].id,
                        },
                    }).then(e => {
                        this.moveLoading = false;
                        if (e.data.code === 200) {
                            this.$message.success(e.data.msg);
                            this.switchGroup(index);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.moveLoading = false;
                    });
                }).catch(() => {
                });
            },
            loadGroups() {
                this.noMall = false;
                this.groupItem = [];
                this.groupList = [];

                let GROUPS_URL = '{{ route('api.attachment.groups') }}';
                let that = this;
                myRequest({
                    url: GROUPS_URL,
                    type: "get",
                }).then(response => {
                    if (response.code !== 200) {
                        that.$message({
                            type: 'error',
                            message: response.msg
                        })
                        return;
                    }
                    that.groupItem = response.data;
                    that.groupList = response.data;
                })
            },
            showAddGroup(index) {
                if (index > -1) {
                    this.groupForm.id = this.groupItem[index].id;
                    this.groupForm.name = this.groupItem[index].title;
                } else {
                    this.groupForm.id = null;
                    this.groupForm.name = '';
                }
                this.groupForm.edit_index = index;
                this.addGroupVisible = true;
            },
            deleteGroup(index) {
                let GROUP_DELETE_URL = '{{ route('api.attachment.deleteGroup', 0) }}';
                let that = this;
                this.$confirm('是否删除分组？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    myRequest({
                        url: GROUP_DELETE_URL.replace(0, this.groupItem[index].id),
                        type: "DELETE",
                    }).then(response => {
                        if (response.code !== 200) {
                            that.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        this.groupItem.splice(index, 1);
                    })
                }).catch(() => {
                });
            },
            groupFormSubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.groupFormLoading = true;
                        let STORE_GROUP_URL = '{{route('api.attachment.saveGroup')}}';
                        myRequest({
                            url: STORE_GROUP_URL,
                            type: "POST",
                            data: Object.assign({}, this.groupForm, {'type': this.type})
                        }).then(response => {
                            if (response.code !== 200) {
                                this.$message({
                                    type: 'error',
                                    message: response.msg
                                })
                                return;
                            }
                            this.addGroupVisible = false;
                            this.groupFormLoading = false;
                            if (this.groupForm.edit_index > -1) {
                                this.groupItem[this.groupForm.edit_index] = response.data;
                            } else {
                                this.groupItem.push(response.data);
                            }
                        }).catch(e => {
                            this.groupFormLoading = false;
                        });
                    }
                })
            },
            switchGroup(index) {
                this.attachments = [];
                this.page = 1;
                this.noMore = false;
                this.loading = true;
                this.uploadParams = {
                    attachment_group_id: index > -1 ? this.groupItem[index].id : null,
                };
                this.currentAttachmentGroupId = index > -1 ? this.groupItem[index].id : null;
                this.loadList();
            },
            loadList() {
                let DATA_URL = '{{route('api.attachment.list')}}';
                this.attachments = [];
                this.$request({
                    url: DATA_URL,
                    method: "GET",
                    params: {
                        page: this.page,
                        page_size: 17,
                        attachment_group_id: this.currentAttachmentGroupId,
                        keyword: this.p_keyword
                    }
                }).then(response => {
                    response = response.data
                    if (response.code !== 200) {
                        this.$message({
                            type: 'error',
                            message: response.msg
                        })
                        this.dialogVisible = false;
                        return;
                    }
                    if (!response.data.data.length) {
                        this.noMore = true;
                    }
                    for (let i in response.data.data) {
                        response.data.data[i].checked = false;
                        response.data.data[i].selected = false;
                        response.data.data[i].duration = null;
                    }
                    this.attachments = response.data.data;
                    this.pagination = {
                        pageSize: response.data.per_page,
                        totalCount: response.data.total
                    };
                    this.checkedAttachments = [];
                    this.loading = false;
                    this.loadingMore = false;
                    // this.updateVideo();
                }).catch(e => {
                    this.groupFormLoading = false;
                });
            },
            handleClick(item) {
                console.log('handleClick', item)
                if (this.showEditBlock) {
                    this.selectItem(item);
                    return;
                }
                if (item.checked) {
                    item.checked = false;
                    for (let i in this.checkedAttachments) {
                        if (item.id === this.checkedAttachments[i].id) this.checkedAttachments.splice(i, 1);
                    }
                    return;
                }
                if (this.multiple) {
                    let checkedCount = 0;
                    for (let i in this.attachments) if (this.attachments[i].checked) checkedCount++;
                    if (this.max && !item.checked && checkedCount >= this.max) return;
                    item.checked = true;
                    this.checkedAttachments.push(item);
                } else {
                    for (let i in this.attachments) this.attachments[i].checked = false;
                    item.checked = true;
                    this.checkedAttachments = [item];
                }
            },
            confirm() {
                console.log('confirm-selected', this.checkedAttachments, this.params);

                this.$emit('selected', this.checkedAttachments, this.params);
                this.dialogVisible = false;
                const urls = [];
                for (let i in this.checkedAttachments) {
                    urls.push(this.checkedAttachments[i].url);
                }
                for (let i in this.attachments) {
                    this.attachments[i].checked = false;
                }
                this.checkedAttachments = [];
                if (!urls.length) {
                    return;
                }
                if (this.multiple) {
                    this.$emit('input', urls);
                } else {
                    this.$emit('input', urls[0]);
                }
            },
            handleStart(files) {
                this.uploading = true;
                this.uploadFilesNum = files.length;
                this.uploadCompleteFilesNum = 0;
            },
            handleSuccess(file) {
                if (file.response && file.response.data && file.response.data.code === 200) {
                    this.loadList()
                    // this.updateVideo();
                }
            },
            handleComplete(files) {
                this.uploading = false;
                if (this.simple) {
                    console.log('handleComplete', files)
                    let urls = [];
                    let attachments = [];
                    for (let i in files) {
                        if (files[i].response.data && files[i].response.data.code === 200) {
                            urls.push(files[i].response.data.data.path);
                            attachments.push(...files[i].response.data.data);
                        }
                    }
                    if (!urls.length) {
                        return;
                    }
                    this.dialogVisible = false;
                    this.$emit('selected', attachments, this.params);
                    if (this.multiple) {
                        this.$emit('input', urls);
                    } else {
                        this.$emit('input', urls[0]);
                    }
                }
            },
            handleLoadMore(currentPage) {
                if (this.noMore) {
                    return;
                }
                this.page = currentPage;
                this.loading = true;
                this.loadingMore = true;
                this.loadList();
            },
            updateVideo() {
                if (!this.canvas) {
                    this.canvas = document.getElementById('app-attachment-canvas');
                }
                for (let i in this.attachments) {
                    if (this.attachments[i].type == 2) {
                        if (this.attachments[i].duration) {
                            continue;
                        }
                        let times = 0;
                        let video = null;
                        const maxRetry = 10;
                        const id = 'app_attachment_' + this._uid + '_' + i;
                        const timer = setInterval(() => {
                            times++;
                            if (times >= maxRetry) {
                                clearInterval(timer);
                            }
                            if (!video) {
                                video = document.getElementById(id);
                            }
                            if (!video) {
                                return;
                            }
                            try {
                                const zoom = 0.15;
                                this.canvas.width = video.videoWidth * zoom;
                                this.canvas.height = video.videoHeight * zoom;
                                this.canvas.getContext('2d').drawImage(video, 0, 0, this.canvas.width, this.canvas.height);
                                this.attachments[i].cover_pic_src = this.canvas.toDataURL('image/jpg');
                            } catch (e) {
                                console.warn('获取视频封面异常: ', e);
                            }

                            if (video.duration && !isNaN(video.duration)) {
                                let m = Math.trunc(video.duration / 60);
                                let s = Math.trunc(video.duration) % 60;
                                m = m < 10 ? `0${m}` : `${m}`;
                                s = s < 10 ? `0${s}` : `${s}`;
                                this.attachments[i].duration = `${m}:${s}`;
                                clearInterval(timer);
                            }
                        }, 500);
                    }
                }
            },
        },
    });
</script>
