<!DOCTYPE html>
<html>
<body>

<p>Click the button to extract parts of the string.</p>

<button onclick="myFunction()">Try it</button>

<p id="demo"></p>

<script>
function myFunction() {
  var str = "Hello,world!";
  var res = str.split(',')
  alert (res[0]);
}
</script>

</body>
</html>
