<?php
/**
 * Created for moneplus.
 * User: tonghe.wei@moneplus.cn
 * Date: 2017/1/6
 * Time: 15:53
 */
?>
<style>
    /*.am-selected{width:100px;}*/
</style>
<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
        <ul class="am-list admin-sidebar-list">

            <li class="admin-parent">
                <a class="am-cf" href="<?= $url[0] ?>">
                    <span class="am-icon-file"></span> 规划结果
                </a>
            </li>
            <li class="admin-parent">
                <a class="am-cf" href="<?= $url[2] ?>">
                    <span class="am-icon-file"></span> 进度列表
                </a>
            </li>
            <li class="admin-parent">
                <a class="am-cf" href="<?= $url[3] ?>">
                    <span class="am-icon-file"></span> 答题记录
                </a>
            </li>

            <li><a href="#" class="loginout"><span class="am-icon-sign-out"></span> 退出</a></li>
        </ul>

    </div>
</div>
<div class="admin-content">
    <div class="admin-content-body">
        <div class="am-cf am-padding am-padding-bottom-0">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">答题记录列表</strong> / <small>List</small></div>
        </div>

        <hr>

        <div class="am-g am-form">
            <div class="am-u-sm-12 am-u-md-2">
                <form action="" class="am-form am-form-inline">
                    开始&nbsp;
                    <div class="am-form-group am-form-icon " style="width:80%;">
                        <i class="am-icon-calendar"></i>
                        <input id="Keystart" type="date" value="<?php if(isset($params['start'])&&!empty($params['start']))echo date('Y-m-d',$params['start']); ?>" class="am-form-field am-input-sm" placeholder="发布日期">
                    </div>
                </form>
            </div>
            <div class="am-u-sm-12 am-u-md-2 am-u-end">
                <form action="" class="am-form am-form-inline">
                    结束&nbsp;
                    <div class="am-form-group am-form-icon " style="width:80%;">
                        <i class="am-icon-calendar"></i>
                        <input id="Keyend" type="date" value="<?php if(isset($params['end'])&&!empty($params['end']))echo date('Y-m-d',$params['end']); ?>" class="am-form-field am-input-sm" placeholder="发布日期">
                    </div>
                </form>
            </div>
            <div class="am-input-group am-input-group-sm">
                <span class="am-input-group-btn">
                    <button id="Keybtn" class="am-btn am-btn-primary" type="button">搜索</button>
                </span>
                <!--<span class="am-input-group-btn">
                    <button id="getExcel" class="am-btn am-btn-success" type="button">导出当前Excel</button>
                </span>-->
            </div>
        </div>
        <div class="am-g">
            <div class="am-u-sm-12">
                <form class="am-form" id="subForm" method="post">
                    <table class="am-table am-table-hover am-scrollable-horizontal">
                        <thead>
                        <tr>
<!--                            <th class="table-check"><input type="checkbox" /></th>-->
                            <th class="table-content">用户代号</th>
                            <th class="table-content">班级号</th>
                            <th class="table-content">课程号</th>
                            <th class="table-content">问题编号</th>
                            <th class="table-content">问题答案</th>
                            <th class="table-content">是否正确</th>
                            <th class="table-content">分数</th>
<!--                            <th class="table-content">公司简介</th>-->
                            <th class="table-date am-hide-sm-only">答题日期</th>
                            <!--<th class="table-set">操作</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($data)){
                            foreach ($data as $k=>$v){
                                echo '<tr>';
                                echo '<td>'.$v['uid'].'</td>';
                                echo '<td>'.$v['classid'].'</td>';
                                echo '<td>'.$v['lessonid'].'</td>';
                                echo '<td>'.$v['questionid'].'</td>';
                                echo '<td>'.$v['answer'].'</td>';
                                echo '<td>'.$v['iscorrect'].'</td>';
                                echo '<td>'.$v['points'].'</td>';
                                echo '<td>'.date('Y-m-d H:i:s',$v['addtime']).'</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="am-cf">
                        共 <?=$params['count']?> 条记录
                        <div class="am-fr">
                            <ul class="am-pagination">
                                <?php $this->widget('application.widgets.pages.ListPagesDWidget', array('pages'=>$pages,'params'=>$params))?>
                            </ul>
                        </div>
                    </div>
                    <hr />
                    <p>注：.....</p>
                </form>
            </div>
        </div>

    </div>
    <div id="bhreason" style="width:300px;margin:0 auto;margin-top:20px;display:none;height:100%;">
        <textarea class="" rows="5" id="bohuicon" style="width:300px;" placeholder="请填写驳回理由"></textarea>
        <div style="margin-top:10px;width:160px;margin:20px auto;">
            <button type="button" class="am-btn am-btn-default am-round" id="delqd">确定</button>
            <button type="button" class="am-btn am-btn-default am-round" id="delqx">取消</button>
        </div>
        <input type="hidden" id="bhid" value=""/>


    </div>

    <footer class="admin-content-footer">
        <hr>
        <p class="am-padding-left"><?= date('Y')?></p>
    </footer>

</div>

<script>
    $(document).on('click', '#Keybtn', function(){
        var Keystart = $("#Keystart").val();
        var Keyend   = $("#Keyend").val();
        var type     = $("#type").val();
        /*var g_a_id     = $("#catetype").val();
        var status     = $("#status").val();
        var title     = $("#title").val();*/
        var url = '<?=$url[0]?>'+'&start='+Keystart+'&end='+Keyend+'&type='+type;
        $("#subForm").attr("action",url);
        $("#subForm").submit();
    });

    $(document).on('click', '#UEdit', function(){
        var url = '<?=$url[1].'&id='?>'+$(this).attr('value');
        layer.open({
            type: 2,
            title: '当品详情',
            shadeClose: true,
            shade: 0.8,
            area: ['700px', '90%'],
            content: url //iframe的url
        });
    });

    $(document).on('click', '#UDel', function(){
        var id = $(this).attr('value');
        /*if(!confirm('您确定要删除吗？')){
            return false;
        }*/
        //询问框
        $("#bhid").val(id);
        layer.open({
            type: 1,
            title:'管理员驳回',
            area: ['420px', '340px'],
            content: $('#bhreason')
        });


    });

</script>
