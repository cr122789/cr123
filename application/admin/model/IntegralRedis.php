<?php
namespace app\admin\model;
use think\Db;
use think\Model;

class IntegralRedis extends Model
{
    protected $name = 'video_integral';
    /**
     *
     * @var object redis client
     */
    private $redis;
    /**
     *
     * @var string 放置排行榜的key
     */
    private $leaderboard;


    // /**
    //  * 构造函数
    //  * @param object $redis 已连接redis的phpredis的对象
    //  * @param string $leaderboard 字符串,排行榜的key名
    //  */
    // public function __construct($redis = [], $leaderboard = '')
    // {

    //     if ($redis) {
    //       $this->redis = $redis;
    //     } else {
    //       $this->redis = new \Redis();
    //       $this->redis->connect('127.0.0.1');
    //       $this->redis->auth('htyl51951490..');//密码认证
    //     }

    //     if ($leaderboard) {
    //         //这里不会检查当前的key值是否存在,是为了方便重新访问对应的排行榜
    //         $this->leaderboard = $leaderboard;
    //     } else {
    //         $this->leaderboard = 'leaderboard:' . mt(1, 100000);
    //         while (!empty($this->redis->exists($this->leaderboard))) {
    //             $this->leaderboard = 'leaderboard:' . mt(1, 100000);
    //         }
    //     }

    // }
    // /**
    //  * 获取当前的排行榜的key名
    //  * @return string
    //  */
    // public function getLeaderboard()
    // {
    //     return $this->leaderboard;
    // }

    //  /**
    //  * 获取给定节点的排名
    //  * @param string $node 对应的节点的key名
    //  * @param string $asc 是否按照分数大小正序排名, true的情况下分数越大,排名越高
    //  * @return 节点排名,根据$asc排序,true的话,第一高分为0,false的话第一低分为0
    //  */
    // public function getNodeRank($node, $asc = true)
    // {
    //     if ($asc) {
    //         //zRevRank 分数最高的排行为0,所以需要加1位
    //         return $this->redis->zRevRank($this->leaderboard, $node);
    //     } else {
    //         return $this->redis->zRank($this->leaderboard, $node);
    //     }
    // }

    // //获取当前用户的积分
    // public function getScore($node)
    // {
    //     return $this->redis->zScore($this->leaderboard, $node);
    // }
    // //增加成员分数
    // public function addNode($node,$count='0'){
    //     return $this->redis->zincrby($this->leaderboard, $count, $node);
    // }
    // /**
    //  * 将对应的值填入到排行榜中
    //  * @param  $node 对应的需要填入的值(比如商品的id)
    //  * @param number $count 对应的分数,默认值为1
    //  * @return Long 1 if the element is added. 0 otherwise.
    //  */
    // public function addLeaderboard($node, $count = 1)
    // {
    //     return $this->redis->zAdd($this->leaderboard, $count, $node );
    // }
    //  //删除
    //  public function remUser($node){
    //     // return $this->redis->zRemRangeByScore($this->leaderboard,'0','100000000');
    //     return $this->redis->zDelete($this->leaderboard, $node);//删除集合中指定数据
    // }
    // public function get($key){
    //     return $this->redis->get($key);
    // }






    /**
     * 给出对应的排行榜
     * @param int $number 需要给出排行榜数目
     * @param bool $asc 排序顺序 true为按照高分为第0
     * @param bool $withscores 是否需要分数
     * @param callback $callback 用于处理排行榜的回调函数
     * @return [] 对应排行榜
     */
    public function getLeadboard($number, $asc = true, $withscores = false,$callback = null)
    {
        // if ($asc) {
        //     $nowLeadboard =  $this->redis->zRevRange($this->leaderboard, 0, $number -1, $withscores);//按照高分数顺序排行;
        // } else {
        //     $nowLeadboard =  $this->redis->zRange($this->leaderboard, 0, $number -1, $withscores);//按照低分数顺序排行;
        // }
        $nowLeadboard=db('users')
            ->field('id,username,nickname,truename,integral')
            ->order('integral desc')
            ->limit(10)
            ->select();

        if ($callback) {
            //使用回调处理
            return $callback($nowLeadboard);
        } else {
            return $nowLeadboard;
        }
    }
   

    //判断季度积分是否开启
    public function isJidu(){
        $jidu=Db::name('video_integral_jiduset')->field('is_open')->where('id=1')->find();
        return $jidu;
    }

    //计算当前年+季度
    public function jidu(){
        $jidu= ceil((date('n'))/3);
        $year=date('Y');
        $data=$year.'_'.$jidu;
        return $data;
    }
    //计算课程是在哪个季度上传的 年+季度
    public function courseJidu($course_id){
        $course=Db::name('video_video')
                ->field('upload_time')
                ->where('id='.$course_id)
                ->find();
        $jidu= ceil((date('n',$course['upload_time']))/3);
        $year=date('Y',$course['upload_time']);
        $data=$year.'_'.$jidu;
        return $data;
    }

    //查看季度积分表中有没有该用户本季度的数据 有的话返回用户信息
    public function isUser($user_id,$jidu){
        $is_user=Db::name('video_integral_jidu')
                ->where('user_id='.$user_id)
                ->where(['jidu'=>$jidu])
                ->find();
        return $is_user;
    }
    //分数不为0的人的个数
    public function hasCount(){
        // return $this->redis->zCount($this->leaderboard, 1, 10000000000000);
        $count=db('users')->where('integral','gt','0')->count();
        return $count;
    }

    //人数个数
    public function userCount(){
        // return $this->redis->zSize($this->leaderboard);
        $count=db('users')->count();
        return $count;
    }

    //分数比自己高的人的个数
    public function gaoCount($score){
        // return $this->redis->zCount($this->leaderboard, $score+1, 10000000000000);
        $count=db('users')->where('integral','gt',$score)->count();
        return $count;
    }

   

    //季度中分数比自己高的人的个数
    public function jdGaoCount($score){
        $result=db('video_integral_jidu')->alias('jidu')->join('mk_users users','jidu.user_id=users.id')->where('jidu.integral','gt',$score)->count();
        return $result;
    }
}