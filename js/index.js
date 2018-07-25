function showMessage(type, message) {
  var x = document.getElementById("snackbar");
  console.log(x);
  switch(type){
    case 'success':
      x.className = "showSuccess";
      x.innerHTML = message;
      setTimeout(function(){ x.className = x.className.replace("showSuccess", ""); }, 3000);
    break;
    case 'error':
      var x = document.getElementById("snackbar");
      x.className = "showError";
      setTimeout(function(){ x.className = x.className.replace("showError", ""); }, 3000);
    break;
    case 'warning':
      var x = document.getElementById("snackbar");
      x.className = "showWarning";
      setTimeout(function(){ x.className = x.className.replace("showWarning", ""); }, 3000);
    break;
    default:
      var x = document.getElementById("snackbar");
      x.className = "showDefault";
      setTimeout(function(){ x.className = x.className.replace("showDefault", ""); }, 3000);
  }
}