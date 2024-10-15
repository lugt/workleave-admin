
<meta charset="utf-8">
<script src="laydate/laydate.js"></script>
<div style="width:970px; margin:10px auto;">
    <input onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" placeholder="请输入日期" class="laydate-icon">
</div>

<script>
;!function(){
laydate.skin('molv');
laydate({
   elem: '#demo'
})

}();
</script>
