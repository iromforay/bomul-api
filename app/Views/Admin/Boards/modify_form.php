
<script>
    $(document).ready(function (){
        $("input:radio[name='writer']:radio[value='<?=$board['writer']?>']").prop("checked",true);
        $("input:radio[name='reader']:radio[value='<?=$board['reader']?>']").prop("checked",true);
        $("input:radio[name='lister']:radio[value='<?=$board['lister']?>']").prop("checked",true);
        $("input:radio[name='reply']:radio[value='<?=$board['reply']?>']").prop("checked",true);
        $("input:radio[name='style']:radio[value='<?=$board['style']?>']").prop("checked",true);
        $("input:radio[name='secret']:radio[value='<?=$board['secret']?>']").prop("checked",true);

    });
</script>
<div class="bs-callout bs-callout-danger">
    <h1>게시판 생성</h1>
</div>

<table class="table table-hover table-dark">
    <tbody>
    <form class="px-md-2" name="boards" action="<?=Admin_SITE?>/Boards/modify" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="board_no" value="<?=$board['boards_no']?>">
        <tr>
            <th scope="row" >게시판코드 <sub>*자동생성</sub></th>
            <td class="table-primary" type="text">
                <input type="hiddens" name="bcode"  value="<?=$board['bcode']?>" readonly/>
            </td>
        </tr>
        <tr>
            <th scope="row" >게시판이름</th>
            <td class="table-primary" type="text">
                <input type="text" name="bname"  class="form-control" value="<?=$board['bname']?>"/>
            </td>
        </tr>
        <tr>
            <th scope="row" >상단타이틀</th>
            <td class="table-primary" type="text">
                <textarea type="text" name="header_title" ><?=$board['header_title']?></textarea>
                <input type="file" name="header_upload"/>
                <?if($board['header_upload']) {?>
                    <img src="<?=PDS_URL.$board['header_dir'].$board['header_upload']?>" width="50%" height="50%" />
                <?}?>
            </td>
        </tr>
        <tr>
            <th scope="row" >하단타이틀</th>
            <td class="table-primary" type="text">
                <textarea type="text" name="footer_title" ><?=$board['footer_title']?></textarea>
                <input type="file" name="footer_upload"/>
                <?if($board['footer_upload']) {?>
                    <img src="<?=PDS_URL.$board['footer_dir'].$board['footer_upload']?>" width="50%" height="50%" />
                <?}?>
            </td>
        </tr>
        <tr>
            <th scope="row" >스타일</th>
            <td class="table-primary" type="text">
                <input type="radio" name="style" value="1" checked> 이미지1(기본스타일)
                <input type="radio" name="style" value="2"> 이미지2(00스타일)
                <input type="radio" name="style" value="3"> 이미지3(00스타일)

            </td>
        </tr>
        <tr>
            <th scope="row" >글쓰기 권한</th>
            <td class="table-primary" type="text">
                <input type="radio" name="writer" value="admin" checked> 관리자만(공지)
                <input type="radio" name="writer" value="member"> 회원만
                <input type="radio" name="writer" value="every"> 누구나 (회원 +비회원)
            </td>
        </tr>
        <tr>
            <th scope="row" >읽기 권한</th>
            <td class="table-primary" type="text">
                <input type="radio" name="reader" value="self" checked> 자신글만(비밀글)
                <input type="radio" name="reader" value="member"> 회원만 읽기
                <input type="radio" name="reader" value="every"> 모두읽기 (회원 +비회원)
            </td>
        </tr>
        <tr>
            <th scope="row" >리스트 권한</th>
            <td class="table-primary" type="text">
                <input type="radio" name="lister" value="self" > 자신글만(비밀글)
                <input type="radio" name="lister" value="every" checked> 모두보기 (회원 +비회원)
            </td>
        </tr>
        <tr>
            <th scope="row" >댓글 기능</th>
            <td class="table-primary" type="text">
                <input type="radio" name="reply" value="Y" checked> 사용
                <input type="radio" name="reply" value="N" > 미사용
            </td>
        </tr>
        <tr>
            <th scope="row" >비밀글 설정</th>
            <td class="table-primary" type="text">
                <input type="radio" name="secret" value="Y" checked> 사용
                <input type="radio" name="secret" value="N" > 미사용
            </td>
        </tr>
    </form>
    </tbody>
</table>
<div class="mb-2">
    <span class="btn btn-primary" onclick="document.boards.submit()">수정</span>
    <span class="btn btn-warning" onclick="location.href='<?=Admin_SITE."/Boards/list"?>'">취소</span>
</div>
<style>
    textarea{width:98%;}
</style>