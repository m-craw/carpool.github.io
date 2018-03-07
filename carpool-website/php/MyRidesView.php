<html>
<head>
	<title>MyRides Page</title>
</head>
<body>

<?php
	echo "Hello, ";
	print $id;
	echo "!!!";
?>

<hr>
My Offered Rides<br>
<?php
	$level=0;
	$prevRoute =$offeredList[0][route_id];
	$prevThread=$offeredList[0][thread_name];
	foreach ($offeredList as $item)
	{
		if ($prevRoute == $item[route_id] && $prevThread == $item[thread_name])
		{
			for($i=0;$i<$level;$i++)
			{
				echo '&nbsp;&nbsp;&nbsp;';
			}
			$level++;
		}
		else
		{
			$level=1;
		}
		echo 'Route_id: <a href="'.$item[route_id].'">'.$item[route_id].'</a> - ThreadName='.$item[thread_name].', Messages:'.$item[num_messages].', message_id='.$item[message_id].',username='.$item[username].', message='.$item[message].'<br>';
		$prevRoute  = $item[route_id];
		$prevThread = $item[thread_name];
	}
?>
<hr>

<hr>
My Requested Rides<br>
<?php
	$level=0;
	$prevRoute=$offeredList[0][route_id];
	$prevThread=$offeredList[0][thread_name];
	foreach ($requestedList as $item)
	{
		if ($prevRoute == $item[route_id] && $prevThread == $item[thread_name])
		{
			for($i=0;$i<$level;$i++)
			{
				print '&nbsp;&nbsp;&nbsp;';
			}
			$level++;
		}
		else
		{
			$level=1;
		}
		print 'Route_id: <a href="'.$item[route_id].'">'.$item[route_id].'</a> - ThreadName='.$item[thread_name].', Messages:'.$item[num_messages].', message_id='.$item[message_id].',username='.$item[username].', message='.$item[message].'<br>';
		$prevRoute = $item[route_id];
		$prevThread = $item[thread_name];
	}
?>
<hr>

</body>
</html>
