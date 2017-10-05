<?php 
require_once("framejazz.php");
$Obj->limit = 20;
$result = $Obj->paginate("SELECT * FROM tb_requests");
echo $Obj->css('default');
echo $Obj->page['link'];
?>
<table border="1px" >
	<tr>
		<td>id</td>
		<td>user id</td>
		<td>tranection</td>
		<td>mobile</td>
		<td>amount</td>
	</tr>
<?php while($data = mysql_fetch_assoc($result)){ ?>
	<tr>
		<td><?php echo $data['id']; ?></td>
		<td><?php echo $data['user_id']; ?></td>
		<td><?php echo $data['transaction_id']; ?></td>
		<td><?php echo $data['mobile']; ?></td>
		<td><?php echo $data['amount']; ?></td>
	</tr>
<?php } ?>
</table>
<?php echo $Obj->page['link']; ?>