<?php

namespace App\Service\Api;
use App\Entity\Post_Entity;
use App\Libraries\Aes;
use App\Libraries\AT_Result;
use App\Service\CI_Service;

class PostService extends CI_Service
{
    protected $code;
    protected $model;
    protected $aes;
    /**
     * @param $model
     */
    public function __construct($log)
    {
        parent::__construct();
        $this->log = $log;
        $this->aes =  new Aes();
        $this->model = model('App\Models\PostModel',true,$this->db);
        return $this;
    }
    public function auth($data, $password)
    {

        $row = $data['row'];
        $header['user_id']= $row['user_id'];

        if($data['permission']['read']=="every"){
            return AT_Result::toSuccessData();
        }else{//회원전용 일땐 회원만
           if($row['user_id ']!="" and $header['user_id']  == $row['user_id']){// 글쓴이가 맞음.pass
               return AT_Result::toSuccessData();
           }else{
               AT_Result::toFailJson("작성자만 열람가능한 비밀글입니다.");
           }
        }
        if($row['secret'] =="T") {//비밀글 , 패스워드 확인
            if ($password == "") {
                AT_Result::toFailJson("비밀글입니다. 패스워드를 입력하세요.");
            } elseif ($row['password'] === password($password)) {
                return AT_Result::toSuccessData();
            } else {
                return AT_Result::toFailJson("패스워드가 틀렸습니다.");
            }
        }

    }

    public function get_csrf($bno=0,$limit=120)
    {
        $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+|';
        $salt = substr(str_shuffle($characters), 0, 5);
        $time = time();
        $hash = md5($bno.$salt.$time.$limit);
        $code = array(
            'bno'=>$bno,
            "salt"=>$salt,
            "time"=>$time,
            "limit"=>$limit,
            'hash' =>$hash
        );
        $text = serialize($code);
        $this->aes->set_key(AES_KEY);
        $enc_string = $this->aes->encrypt($text);

        return $enc_string;
    }
    public function verify($bno=0,$hash)
    {
        // 값 검증
        $this->aes->set_key(AES_KEY);
        $text = $this->aes->decrypt($hash);

        return $this->csrf_verify($bno,unserialize($text));

    }
    public function csrf_verify($_bno,$code)
    {
        $at_result  = new AT_Result();
        $salt = $code['salt'];
        $time = $code['time'];
        $bno = $code['bno'];
        $limit = $code['limit'];
        $hash = md5($bno.$salt.$time.$limit);

        //발급한 게시물 체크
        if($bno !== $_bno) return AT_Result::toFailData('다시 시도하세요','BE01');
        //시간 체크
        else if(time() >$time+$limit) return AT_Result::toFailData('다시 시도하세요','BE02');
        else if($hash === $code['hash'] or $hash === $code['HASH']) return AT_Result::toSuccessData();
        else return AT_Result::toFailData('다시 시도하세요','BE03');
    }

    public function set_code($code)
    {
        $this->code = $code;
        return $this;
    }

    public function get_list_grant($data)
    {
        return $this;
    }
    public function getbyid($post_no)
    {
        $row = $this->model->findbyid($post_no);
        $row = $this->after_process($row);
        return $row;
    }

    public function viewcount($post_no)
    {
        $entity = new Post_Entity();
        $entity->setPostNo($post_no);

        $this->model->viewcount($entity);
    }
    public function get_list($page,$perPage,$where="")
    {
        $start = ($page -1)*$perPage;
        if($this->code == "notice"){
            $code = array("notice","event");
        }else{
            $code = $this->code;
        }
        $total_count  = $this->model->setCode($code)->get_count();
        $this->log->info("board count Query : ".$this->model->getQuery());
        $row = $this->model->get_list($start,$perPage,$where);
        $this->log->info("board list Query : ".$this->model->getQuery());
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);
    }
    //수정가능 권한 체크 및 데이터 리턴
    public function is_modify($post_no)
    {
        $row = $this->getbyid($post_no);
        /*체크*/
        if($row['user_id']!=""){ //회원등록글이면
            if($row['user_id']  == $_SESSION['user_id']) {//등록자와 로그인 ID가 같다면 패스
                AT_Result::toSuccessData($row);
            }else{ //다르면 list로 리턴
                AT_Result::toFailData("","WRONG_AUTH");
            }

        }else{ //비회원 등록글이면
            AT_Result::toFailData("","IN_PWD");
            /*
            1-1 패스워드 비교!!
                1-1-1 같으면 패스
                1-1-2 다르면 list로 리턴
        */
        }

    }

    public function after_process($data)
    {
        foreach($data as $k=>$v){
            $value['board_no'] = $v['post_no']??$v['board_no'];
            $value['board_type'] = $v['bcode']??$v['board_type'];
            $value['score'] = $v['score']??"";
            $value['board_title'] = $v['subject'];
            $value['board_writer'] = $v['name'];
            $value['board_date'] = $v['write_date'];
            $value['board_hit'] = $v['view_count']??0;
            $value['board_status'] = $v['status'] ? "Y" : "N" ;
            $value['board_content'] = html_entity_decode($v['content']);


            if($v['bcode']=="review") {
                $value['score'] = $v['add01'];
            }
            if($v['bcode'] =="vod") {
                $value['movie_url'] = $v['add02'];
                $value['file_name'] = $v['orgin_name'];
                $value['file'] = SITE_PDS.$v['file_dir'].$v['file_name'];
            }


            $board_list[$k] =  $value;
        }
        return $board_list;
    }
    public function save($data=null)
    {
        $entity = new Post_Entity();
        $data['title'] = esc($data['title']);
        $data['content'] = esc($data['content']);

        /*entity 주입*/
        $entity->setSubject($data['title']);
        $entity->setContent($data['content']);
        $entity->setName($data['name']);
        $entity->setSecret($data['secret']);
        $entity->setPassword($this->password($data['password']));
        $entity->setBcode($data['bcode']);
        $entity->setTel($data['tel']);

        $post_no = $this->model->sql_save($entity);
        return $post_no;
    }

    public function password($text)
    {
        $password = $text;

        return $password;
    }
    public function is_auth($data,$password="")
    {
        //비밀글 여부 확인
        if($data['secret']=="T"){//비밀글일때 , 쓰니가 회원인가?
            if($data['user_id']==""){//비회원이쓴 비밀글은 반드시 비번을 입력 받아야함.
                if($password=="") {
                    return AT_Result::toFailData("패스워드를 입력하세요", "IN_PWD");
                }else if($data['password'] == $this->password($password) ) { //비번 확인
                    return AT_Result::toSuccessData();
                }else{
                    return AT_Result::toFailData("패스워드가 틀렸습니다.", "IN_PWD");
                }
            }else if($data['user_id'] == $_SESSION['user_id']){ //회원이쓴 비밀글은 글쓴 회원이라면 읽을수 있다.
                return AT_Result::toSuccessData();
            }
        }else{ //아닐때
            return AT_Result::toSuccessData();
        }
    }
}