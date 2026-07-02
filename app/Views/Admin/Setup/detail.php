<link rel="stylesheet" href="<?=RS_TH?>/jquery-ui-1.13.2/jquery-ui.min.css" >
<script src="<?=RS_TH?>/jquery-ui-1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?=RS_TH?>/evol-colorpicker3.4.3/evol-colorpicker.css" >
<script src="<?=RS_TH?>/evol-colorpicker3.4.3/evol-colorpicker.js"></script>
<script>
    $(document).ready(function (){
        /*page init*/
        $("input:radio[name='type']:radio[value='<?=$row['type']?>']").prop("checked",true);
        $("input:radio[name='uses']:radio[value='<?=$row['uses']?>']").prop("checked",true);
        $("input:radio[name='link_type']:radio[value='<?=$row['link_type']?>']").prop("checked",true);
        switch_link_type("<?=$row['link_type']?>");

        //link_re link_bo link_pa
        $(document).on("click","input[name='link_type']",function (){
            const link_type = $(this).val();
            switch_link_type(link_type);

        });
        $(document).on("click",".link",function (){
            const link_code = $(this).val();
            $("input[name='link']").val(link_code);
        })
        /*color picker setup*/
        $(document).on("click","#bg_color",function (){
            $("#bg_color").colorpicker({

                val: "<?=$row['bg_color']?>",
                initialHistory: ["#ff0000", "#000000", "red", "purple"],
            });
        });
        $(document).on("click","#color",function (){
            $("#color").colorpicker({

                val: "<?=$row['bg_color']?>",
                initialHistory: ["#ff0000", "#000000", "red", "purple"],
            });
        });
        $("#color").on("change.color", function (event, color) {
            setColor(color,"color");
        });
        $("#bg_color").on("change.color", function (event, color) {
            setColor(color,"bg_color");

        });
    });
    function switch_link_type(link_type)
    {
        console.log("링크 타입  : "+link_type);
        $("input[name='link']").attr("type","hidden");
        if(link_type =="no") { //링크없음.
            $("#link_out").hide();
            $("#link_in").hide();
            $("input[name='link']").val("");
        }else if(link_type == "re") {  //외부경로링크
            $("#link_out").show();
            $("#link_in").hide();
            $("input[name='link']").attr("type","text");
            $("input[name='link']").attr("placeholder","ex)https://www.naver.com");
            $("input[name='link']").val("<?=$row['link']?>");
        }else{ //내부 컨텐츠 링크 -게시판 or 페이지
            $("#link_out").hide();
            $("#link_in").show();
            if(link_type=="bo"){
                $(".board_link").show();
                $(".pager_link").hide();
            }else{ //pa
                $(".board_link").hide();
                $(".pager_link").show();
            }

        }
    }
    function setColor(color,target)
    {
        if (color) {
            $(`#${target}`).css("background-color", color);
            $(`#${target}_code`).text(color);
            $(`input[name='${target}']`).val(color);

            $(`#${target}`).colorpicker("destroy");
        }
    }
</script>
<div class="bs-callout bs-callout-danger">
    <h1>홈페이지 수정</h1>
</div>

<table class="table table-hover table-dark">
    <tbody>
    <form class="px-md-2" name="setup" action="<?=Admin_SITE?>/Setup/modify" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="build_no" value="<?=$row['build_no']?>">
        <tr>
            <th scope="row" >사용여부</th>
            <td class="table-primary" type="text">
                <input type="radio" name="uses" value="Y" checked> 사용
                <input type="radio" name="uses" value="N"> 미사용
            </td>
        </tr>
        <tr>
            <th scope="row" >이름</th>
            <td class="table-primary" type="text">
                <?=$row['build_title']?>
            </td>
        </tr>
        <tr>
            <th scope="row" >위치</th>
            <td class="table-primary" type="text">

            </td>
        </tr>
        <tr>
            <th scope="row" >타입</th>
            <td class="table-primary" type="text">
                <input type="radio" name="type" value="text" checked> 텍스트
                <input type="radio" name="type" value="image"> 이미지
            </td>
        </tr>
        <tr>
            <th scope="row" >이미지</th>
            <td class="table-primary" type="text">
                <?if($row['file_name'] !="") {?>
                <span>
                    <img src="<?=PDS_URL . $row['img_url'] . $row['file_name']?>" width="50" height="50">
                </span>
                <?}?>
                <input type="file" name="img_file">
            </td>
        </tr>
        <tr>
            <th scope="row" >내용</th>
            <td class="table-primary" type="text">
                <textarea name="text"><?=$row['text']?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row" >텍스트 배경색상</th>
            <td class="table-primary" type="text">
                <div class="color_pick" id="bg_color" style="background: <?=$row['bg_color']?>"></div>
                <span class="color_code" id="bg_color_code"><?=$row['bg_color']?></span>
                <input type="hidden" name="bg_color" value="<?=$row['bg_color']?>">
            </td>
        </tr>
        <tr>
            <th scope="row" >텍스트 폰트색상</th>
            <td class="table-primary" type="text">
                <div class="color_pick" id="color" style="background: <?=$row['color']?>"></div>
                <span class="color_code" id="color_code"><?=$row['color']?></span>
                <input type="hidden" name="color" value="<?=$row['color']?>">
            </td>
        </tr>
        <tr>
            <th scope="row" >링크타입</th>
            <td class="table-primary" type="text">
                <input type="radio" name="link_type" value="bo"> 게시판
                <input type="radio" name="link_type" value="pa"> 페이지
                <input type="radio" name="link_type" value="re"> 외부링크
                <input type="radio" name="link_type" value="no" checked> 없음
            </td>
        </tr>
        <tr id="link_out">
            <th scope="row" >링크</th>
            <td class="table-primary" type="text">
                <input type="hidden" name="link" value="<?=$row['link']?>" maxlength="255" style="width: 350px;" />
            </td>
        </tr>
        <tr id="link_in">
            <th scope="row" >링크</th>
            <td class="table-primary" type="text">
                <select name="bo_link" class="board_link link">
                    <?foreach($link['board'] as $k=>$v) {?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?}?>
                </select>
                <div class="board_link"><span>게시물 노출 개수</span>
                    <select name="bo_show_count">
                        <?for($i=0;$i<=$limit_showcount;$i++){
                            $select = ($row['show_count'] ==$i)? "selected":"";
                        ?>
                        <option value="<?=$i?>" <?=$select?>><?=$i?></option>
                        <?}?>
                    </select> </div>
                <select name="pa_link" class="pager_link link">
                    <?foreach($link['pager'] as $k=>$v) {?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?}?>
                </select>
            </td>
        </tr>
        
    </form>
    </tbody>
</table>
<div class="mb-2">
    <span class="btn btn-primary" onclick="document.setup.submit()">수정</span>
    <span class="btn btn-warning" onclick="location.href='<?=Admin_SITE."/Setup/Home"?>'">취소</span>
</div>
<style>
    textarea{width:98%;}
    .color_pick{position: absolute;width: 25px;height: 25px;cursor: pointer;}
    .color_code{position: relative;left:35px;}
    .evo-pop { left:27px}
    #link_out, #link_in {
        display: none;
    }
</style>