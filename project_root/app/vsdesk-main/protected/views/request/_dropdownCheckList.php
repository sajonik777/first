<?php

/** @var CClientScript $cs */
$cs=Yii::app()->clientScript;
$cs->registerCss('DropdownCheckListCSS', '
.dropdown-check-list {
  display: inline-block;
  border: 1px solid #ccc;
}
.dropdown-check-list .anchor {
  position: relative;
  cursor: pointer;
  display: inline-block;
  padding: 5px 50px 5px 10px;
//  border: 1px solid #ccc;
}
.dropdown-check-list .anchor:after {
  position: absolute;
  content: "";
  border-left: 2px solid black;
  border-top: 2px solid black;
  padding: 5px;
  right: 10px;
  top: 20%;
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}
.dropdown-check-list .anchor:active:after {
  right: 8px;
  top: 21%;
}
.dropdown-check-list ul.items {
  padding: 2px;
  display: none;
  margin: 0;
//  border: 1px solid #ccc;
//  border-top: none;
  border-top: 1px solid #ccc;
}
.dropdown-check-list ul.items li {
  list-style: none;
}
.dropdown-check-list ul.items li input {
    margin: 0;
  width: 20px;
}
        ');
$cs->registerScript('DropdownCheckListJS', "
    var checkList = document.getElementById('list1');
    if(checkList){
    var items = document.getElementById('items');
    checkList.getElementsByClassName('anchor')[0].onclick = function (evt) {
        if (items.classList.contains('visible')){
            items.classList.remove('visible');
            items.style.display = 'none';
        } else{
            items.classList.add('visible');
            items.style.display = 'block';
        }
    }

    items.onblur = function(evt) {
        items.classList.remove('visible');
    }
  }
");
?>
<?php
$checklist = '
    <div id="list1" class="dropdown-check-list" tabindex="100">
        <span class="anchor">Статус</span>
        <ul id="items" class="items">';
foreach (Status::all() as $status){
    $check = '';
    if(isset($_GET['Request']['slabel']) && in_array($status, $_GET['Request']['slabel'])){
        $check = 'checked';
    }
    $checklist .= '<li><input type="checkbox" value="' . $status . '" name="Request[slabel][]" ' . $check . '/>'. $status .'</li>';
}
$checklist .= '</ul>
    </div>';
?>

<?php
$checklistFullFields = '
    <div id="list1" class="dropdown-check-list" tabindex="100">
        <span class="anchor">Статус</span>
        <ul id="items" class="items">';
foreach (Status::all() as $status){
    $check = '';
    if(isset($_GET['RequestFullFields']['slabel']) && in_array($status, $_GET['RequestFullFields']['slabel'])){
        $check = 'checked';
    }
    $checklistFullFields .= '<li><input type="checkbox" value="' . $status . '" name="RequestFullFields[slabel][]" ' . $check . '/>'. $status .'</li>';
}
$checklistFullFields .= '</ul>
    </div>';
?>