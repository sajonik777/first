<?php

echo '<span class="design-popup" id="tree-'.$options['id'].'" style="width:'.($options['width'] != '100%' ? $options['width'].'px' : $options['width']).' display:inline-block; margin: 0px;">
		<table cellspacing="0" cellpadding="0" class="d-tree" >
		<tbody>
		<tr>
			<td class="d-tree-t-l"></td>
			<td class="d-tree-t-c"></td>
			<td class="d-tree-t-r"></td>
		</tr>
		<tr>
			<td class="d-tree-c-l"></td>
			<td class="d-tree-c-c" >';
				//<div class="up-button">'.$options['labels']['up'].'</div> 

echo			'<div class="clear"></div>
				<span id="tree-content-'.$options['id'].'" style="height:'.$options['height'].'px; display:block; overflow: '.$options['overflow'].'; " class="tree-content" ></span>';
				//<div class="down-button">'.$options['labels']['down'].'</div>

echo		'	</td>
			<td class="d-tree-c-r"></td>
		</tr>
		<tr>
			<td class="d-tree-b-l"></td>
			<td class="d-tree-b-c"></td>
			<td class="d-tree-b-r"></td>
		</tr>
		</tbody></table></span>';
?>