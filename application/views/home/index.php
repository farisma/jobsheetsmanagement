<div class="hero-unit">
    <h1>Hello Andon Team!</h1>
    <p class="lead">Welcome to Timesheet Management Application</p>
	<div style="width:335px; height:40px; float:right; ">
	<?php 
	/*if(isset($genre)) {
	  echo "<ul id='genrelist'><li id='all' class='selected'>All</li>";
	   foreach($genre as $keys=>$values){
	               echo  "<li id=".$keys.">".$values."</li>";
	   }
	   echo "</ul>";
	}  */
	?>
	 
	</div>
	<div style="clear:both;"></div>
	<div>
	<ul class="list" id="list">
	<?php  /*foreach($feeddieline as  $key=>$values) {
	echo "<li class=".$feeddieline_class.">";
	echo "<h4>".$values["title"]."</h4>";
	echo "<p>".$values["description"]."<a href='".$values['link']."' target='_blank' class='readmore'> Read more...</a></p>";
	if($values["author"] != NULL) {echo "<span class='author'>Author:  <strong>". $values["author"]." </strong></span><br><br>";}
	echo "<span class='publishdate'>Published on: ".$values["pubDate"]."</span><br><br>";	
	echo "</li>";
	
	} */?>

	<?php  /*foreach($designmilk as  $key=>$values) {
	echo "<li class=".$designmilk_class.">";
	echo "<h4>".$values["title"]."</h4>";
	echo "<p>".$values["description"]."<a href='".$values['link']."' target='_blank' class='readmore'> Read more...</a></p>";
	if($values["author"] != NULL) {echo "<span class='author'>Author: <strong>". $values["author"]."</strong></span><br><br>";}
	echo "<span class='publishdate'>Published on: ".$values["pubDate"]."</span><br><br>";	
	echo "</li>";
	
	} */?>
	

	<?php  /*foreach($wamda as  $key=>$values) {
	echo "<li class=".$wamda_class.">";
	echo "<h4>".$values["title"]."</h4>";
	echo "<p>".$values["description"]."<a href='".$values['link']."' target='_blank' class='readmore'> Read more...</a></p>";
	if($values["author"] != NULL) {echo "<span class='author'>Author: <strong>". $values["author"]."</strong></span><br><br>";}
	echo "<span class='publishdate'>Published on: ".$values["pubDate"]."</span><br><br>";	
	echo "</li>";
	
	}*/?>
	
	<?php  /*foreach($mashable as  $key=>$values) {
	echo "<li class=".$mashable_class.">";
	echo "<h4>".$values["title"]."</h4>";
	echo "<p>".$values["description"]."<a href='".$values['link']."' target='_blank' class='readmore'> Read more...</a></p>";
	if($values["author"] != NULL) {echo "<span class='author'>Author: <strong>". $values["author"]."</strong></span><br><br>";}
	echo "<span class='publishdate'>Published on: ".$values["pubDate"]."</span>";	
	echo "</li>";
	
	} */ ?>
	</ul>
	</div>
</div>

<script type="text/javascript">
/*var list = document.getElementById("list");
function shuffle(items)
{

    var cached = items.slice(0), temp, i = cached.length, rand;
	
    while(--i)
    {
        rand = Math.floor(i * Math.random());
        temp = cached[rand];
		
        cached[rand] = cached[i];
		
        cached[i] = temp;
		
    }
    return cached;
}
function shuffleNodes()
{
    var nodes = list.children, i = 0;
	
    nodes = Array.prototype.slice.call(nodes);
    nodes = shuffle(nodes);
    while(i < nodes.length)
    {
        list.appendChild(nodes[i]);
        ++i;
    }
}
$(document).ready(function() {
shuffleNodes();
});

$(document).ready(function(){
$("ul#genrelist li").click(function(){
  
  var currentId = $(this).attr('id');
  $('ul#genrelist li:not(#'+currentId+')').removeClass("selected");
  
   
   if($(this).not(".selected")) {
	 $(this).addClass("selected");
	 if(currentId != "all") {
	   $('ul#list li:not(.'+currentId+'_list)').hide("slow","linear",function(){
		  $('ul#list li.'+currentId+'_list').show("slow","linear");
	   }); 
	   }
	   else if(currentId == "all")
	   {
		  $('ul#list li').show("show","linear");
	   }
	 
	 }
});
}); */
</script>