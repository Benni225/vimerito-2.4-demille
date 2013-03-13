<div class="row">
	<div class="span12">
		<h3>Lesson1: The controller</h3>
	</div>
</div>
<div class="row">
	<div class="span12">
		<p class="lead">
			In this lesson we created a controller an sended data
			to the template. The template we sended to the layout.
		</p>
	</div>
</div>
<div class="row">
	<div class="span12">
		<h5>{$title}</h5>
		<p>{$text}</p>
	</div>
</div>
<div class="row">
	<div class="span12">
		See this site:
		<ul>
		<li><a href="<?=Url::to("lesson1@noLayout");?>">Without the layout</a></li>
		<li><a href="<?=Url::to("lesson1");?>">With the layout</a></li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="span12">
		Go to the next lesson: <a href="<?=Url::to("lesson2");?>">Click here!</a>
	</div>
</div>