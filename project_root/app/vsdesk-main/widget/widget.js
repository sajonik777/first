var link = document.createElement("link");
link.setAttribute("rel", "stylesheet");
link.setAttribute("type", "text/css");
link.setAttribute("href", univefWidget.url + "/widget/widget.css");
document.getElementsByTagName("head")[0].appendChild(link);

var button = document.createElement('div');
button.id = "button_univef";
button.innerText = "✉";
button.className = univefWidget.position;
button.style = 'background-color: ' + univefWidget.color;
document.getElementsByTagName("body")[0].appendChild(button);

var modal = document.createElement('div');
modal.innerHTML =
    '<div id="univef_widget_modal" class="vsmodal" >' +
    '<div id="frame"><a id="close_btn" href="#close" title="Закрыть"></a></div></div>';

document.getElementsByTagName("body")[0].appendChild(modal);
var frame = document.createElement('iframe');
frame.src = univefWidget.url + "/portal/widget";
frame.width = "100%";
frame.height = "100%";
frame.scrolling = "no";
document.getElementById('frame').appendChild(frame);

button.addEventListener('click', function () {
    document.getElementById('univef_widget_modal').classList.add("modalShow");
});

document.getElementById('close_btn').addEventListener('click', function () {
    document.getElementById('univef_widget_modal').classList.toggle("modalShow");
});

if(univefWidget.animate == 'true'){
    var styleSheetElement = document.createElement("style"), customStyleSheet;
    document.head.appendChild(styleSheetElement);
    customStyleSheet = document.styleSheets[0];
    customStyleSheet.insertRule("@-webkit-keyframes button_univef {0% {box-shadow: 0 0 6px 4px rgba(23, 167, 167, 0), 0 0 0px 0px rgba(0, 0, 0, 0), 0 0 0px 0px rgba(23, 167, 167, 0);}10% {box-shadow: 0 0 4px 4px #d1dad7, 0 0 12px 10px rgba(0, 0, 0, 0), 0 0 12px 12px "+univefWidget.color+";}100% {box-shadow: 0 0 4px 4px rgba(23, 167, 167, 0), 0 0 0px 40px rgba(0, 0, 0, 0), 0 0 0px 20px rgba(23, 167, 167, 0);}}");
    // customStyleSheet.insertRule("@-moz-keyframes button_univef {0% {box-shadow: 0 0 6px 4px rgba(23, 167, 167, 0), 0 0 0px 0px rgba(0, 0, 0, 0), 0 0 0px 0px rgba(23, 167, 167, 0);}10% {box-shadow: 0 0 6px 4px #d1dad7, 0 0 12px 10px rgba(0, 0, 0, 0), 0 0 10px 12px "+univefWidget.color+";}100% {box-shadow: 0 0 6px 4px rgba(23, 167, 167, 0), 0 0 0px 40px rgba(0, 0, 0, 0), 0 0 0px 20px rgba(23, 167, 167, 0);}}");
    customStyleSheet.insertRule("@keyframes button_univef {0% {box-shadow: 0 0 6px 4px rgba(23, 167, 167, 0), 0 0 0px 0px rgba(0, 0, 0, 0), 0 0 0px 0px rgba(23, 167, 167, 0);}10% {box-shadow: 0 0 6px 4px #d1dad7, 0 0 12px 10px rgba(0, 0, 0, 0), 0 0 10px 12px "+univefWidget.color+";}100% {box-shadow: 0 0 6px 4px rgba(23, 167, 167, 0), 0 0 0px 40px rgba(0, 0, 0, 0), 0 0 0px 20px rgba(23, 167, 167, 0);}}");
}