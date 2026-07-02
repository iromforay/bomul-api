<script>
    $(document).ready(function (){
        $("input:radio[name='reader']:radio[value='<?=$pager['reader']?>']").prop("checked",true);
        $("input:radio[name='style']:radio[value='<?=$pager['style']?>']").prop("checked",true);
    });
    function add()
    {
        const now_count = $("input[name='pager_dno[]']").length;
        const max_count= <?=$max_count?>;
        if(now_count<max_count) {
            console.log(`now:${now_count} , max : ${max_count}`);
            html = make_form(now_count + 1);
            $("#content").after(html);
        }else{
            alert(`최대 허용개수 ${max_count}를 초과하여 생성 할수 없습니다.`);
        }
    }
    function del()
    {

    }
    function make_form(next_no)
    {
        let form = `<tr id='content'>\
            <th scope='row'>\
        내용\
        <span class='btn btn-danger' onclick='del();'>삭제</span>\
    </th>\
        <td class='table-primary content' type='text'>\
            <input type='hidden' name='number' value='${next_no}'>\
                <input type='hidden' name='pager_dno[]' value=''>\
                    <input type='hidden' name='sorty[]' value=''>\
                        <textarea name='body[]'></textarea>\
        </td>\
    </tr>`;

        return form;
    }
</script>
<div class="bs-callout bs-callout-danger">
    <h1>페이지 수정</h1>
</div>

<table class="table table-hover table-dark">
    <tbody>
    <form class="px-md-2" name="pager" action="<?=Admin_SITE?>/Pager/modify" method="POST">
        <input type="hidden" name="seq_no" value ="<?=$pager['pager_no']?>" >
        <tr>
            <th scope="row" >코드 <sub>*자동생성(수정불가)</sub></th>
            <td class="table-primary" type="text">
                <input type="hiddens" name="code"  value="<?=$pager['code']?>" readonly/>
            </td>
        </tr>
        <tr>
            <th scope="row" >이름</th>
            <td class="table-primary" type="text">
                <input type="text" name="title"  class="form-control" value="<?=$pager['title']?>"/>
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
            <th scope="row" >읽기 권한</th>
            <td class="table-primary" type="text">
                <input type="radio" name="reader" value="member"> 회원만 공개
                <input type="radio" name="reader" value="every" checked> 모두공개 (회원 +비회원)
            </td>
        </tr>
    <? if(count($detail)>0) {?>
        <?foreach($detail as $k =>$v) {?>
        <tr id="content">
            <th scope="row">
                내용
                <?if($k==0){?>
                <span class="btn btn-danger" onclick="add();">추가</span>
                <?}?>
            </th>
            <td class="table-primary content" type="text">
                <input type="hidden" name="number" value="<?=$k+1?>">
                <input type="hidden" name="pager_dno[]" value="<?=$v['pager_dno']?>">
                <input type="hidden" name="sorty[]" value="<?=$v['sorty']?>">
                <textarea name="body[]"><?=$v['body']?></textarea>
            </td>
        </tr>
    <?}// end foreach
    }else { ?>
        <tr id="content">
            <th scope="row">
                내용
                    <span class="btn btn-danger" onclick="add();">추가</span>
            </th>
            <td class="table-primary content" type="text">
                <input type="hidden" name="number" value="">
                <input type="hidden" name="pager_dno[]" value="">
                <input type="hidden" name="sorty[]" value="">
                <textarea name="body[]"></textarea>
            </td>
        </tr>
    <?} ?>
    </form>
    </tbody>
</table>
<div class="mb-2">
    <span class="btn btn-primary" onclick="document.pager.submit()">등록</span>
    <span class="btn btn-warning" onclick="location.href='<?=Admin_SITE."/Pager/list"?>'">취소</span>
</div>
<style>
    textarea{width:98%;}
</style>