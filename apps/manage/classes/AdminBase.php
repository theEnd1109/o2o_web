<?php
namespace CLASSES;
use Swoole;
use App;
/**
* @action   管理员增删改查
* @author   户连超
* @addtime  2017.08.01
* @e-mail   zrkjhlc@gmail.com
*/
class AdminBase extends Swoole\Controller
{
    /**
     * 消息数据
     * @var
     */
    private $msgData;

    function __construct($swoole)
    {
        parent::__construct($swoole);
        $this->session->start();

        $this->public_assign();

        $_SESSION['manager_id'] = 0;//管理员添加时记录管理员id现在只是临时数据 等longin功能完成后再做修改;

    }
    protected function encrypt($password)
    {
        return md5($password);
    }
    public function public_assign()
    {
        if (defined("MANAGEURL")) {
            $this->tpl->assign("manageurl",MANAGEURL);
        }
    }
    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态(0成功，1失败)
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
   protected function show_msg($message,$status=1,$jumpUrl='',$time = 3) {
        // if(true === $ajax) {// AJAX提交$ajax=false
        //     $data           =   is_array($ajax)?$ajax:array();
        //     $data['info']   =   $message;
        //     $data['status'] =   $status;
        //     $data['url']    =   $jumpUrl;
        //     $this->ajaxReturn($data);
        // }
        // if(is_int($ajax)) $this->assign('waitSecond',$ajax);
        if(!empty($jumpUrl)) $this->assign('jumpUrl',$jumpUrl);
        // 提示标题
        $this->tpl->assign('msgTitle',$status ? "操作成功" : "操作失败");

        $this->tpl->assign('status',$status);   // 状态

        if($status) { //发送成功信息
            $this->tpl->assign('message',$message);// 提示信息
            // 成功操作后默认停留3秒
            $this->tpl->assign('waitSecond',$time);
            // 默认操作成功自动返回操作前页面
            if (empty($jumpUrl)) {
                $this->tpl->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
            }
            $this->tpl->display("manager/show_msg.php");
            exit ;
        }else{
            $this->tpl->assign('error',$message);// 提示信息
            //发生错误时候默认停留3秒
            $this->tpl->assign('waitSecond',$time);
            // 默认发生错误的话自动返回上页
            $this->tpl->assign('jumpUrl',"javascript:history.back(-1);");
            $this->tpl->display("manager/show_msg.php");
            // 中止执行  避免出错后继续执行
            exit ;
        }
    }
}