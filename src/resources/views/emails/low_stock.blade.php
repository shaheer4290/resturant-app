<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
   
<center>
<h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
    Ingredient Low Stock Alert
</h2>
</center>
  
<p>Hi {{ $ingredient->merchant->name }}, </p>

<p>Following ingredient has low stock :</p>
<h2> {{ $ingredient->name }} </h2>
<p> current stock :  <b>{{ $ingredient->current_stock  }} </b> {{ $ingredient->unit }}</p>
<p> initial stock :  <b>{{ $ingredient->initial_stock }}</b> {{ $ingredient->unit }}</p>
  
<strong>Kindly check out the stock</strong>
  
</body>
</html>