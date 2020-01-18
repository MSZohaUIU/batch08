<!DOCTYPE html>
<html>
<head>
	<title></title>
<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>

	<div class="card">
  <h5 class="card-header">Featured</h5>
  <div class="card-body">
    <div class="form-group">
    <label for="exampleInputPassword1">Name: </label>
    <span>{{$data['name']}}</span>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Email: </label>
    <span>{{$data['email']}}</span>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Message: </label>
    <span>{{$data['message']}}</span>
  </div>
  </div>
</div>
<a href="{{url('/')}}"	class="btn btn-primary">Back To Home</a>
</body>
</html>
