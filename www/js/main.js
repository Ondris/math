 $(document).ready(function(){
     $.nette.init();
     
    $(".showTask").click(function() {
	var order = $(".showTask").index(this);
	$(".task:eq(" + order + ")").toggle();
    });
    
    $(".showHelp").click(function(){
	var order = $(".showHelp").index(this);
	$(".help:eq(" + order + ")").toggle();
    });
 });