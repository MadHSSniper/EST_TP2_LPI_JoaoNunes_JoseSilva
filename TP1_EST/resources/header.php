

<style>
/* Remove the navbar's default margin-bottom and rounded borders */ 
.navbar {
  margin-bottom: 0;
  border-radius: 0;
}

/* Set height of the grid so .sidenav can be 100% (adjust as needed) */
.row.content {height: 450px}

/* Set gray background color and 100% height */
.sidenav {
  padding-top: 20px;
  background-color: #f1f1f1;
  height: 100%;
}

/* Set black background color, white text and some padding */
footer {
  background-color: #555;
  color: white;
  padding: 15px;
}

/* On small screens, set height to 'auto' for sidenav and grid */
@media screen and (max-width: 767px) {
  .sidenav {
    height: auto;
    padding: 15px;
  }
  .row.content {height:auto;} 
}
</script>
<script>
body {
  position: relative;
}
ul.nav-pills {
  top: 20px;
  position: fixed;
}
div.col-sm-9 div {
  height: 250px;
  font-size: 28px;
}
#section1 {color: #fff; background-color: #1E88E5;}
#section2 {color: #fff; background-color: #673ab7;}
#section3 {color: #fff; background-color: #ff9800;}
#section41 {color: #fff; background-color: #00bcd4;}
#section42 {color: #fff; background-color: #009688;}

@media screen and (max-width: 810px) {
  #section1, #section2, #section3, #section41, #section42  {
    margin-left: 150px;
  }
}
</style>