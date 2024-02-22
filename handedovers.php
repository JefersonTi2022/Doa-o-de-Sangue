<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Lista de Solicitações</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_handover">
					<i class="fa fa-plus"></i> Novo
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Data</th>
									<th class="">Codigo da Solicitação</th>
									<th class="">Nome do paciente</th>
									<th class="">Grupo Saguíneo</th>
									<th class="">Informações</th>
									<th class="text-center">Editar/Excluir</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$handovers = $conn->query("SELECT hr.*,r.patient,r.blood_group, r.volume,r.ref_code FROM handedover_request hr inner join requests r on r.id = hr.request_id order by date(hr.date_created) desc ");
								while($row=$handovers->fetch_assoc()):
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $row['ref_code'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['patient']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['blood_group'] ?></b></p>
									</td>
									<td class="">
										 <p>Volume Doado: <b><?php echo  ($row['volume'] / 1000).' L' ?></b></p>
										 <p>Recebido por: <b><?php echo ucwords($row['picked_up_by']) ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_handover" type="button" data-id="<?php echo $row['id'] ?>" >Editar</button>
										<button class="btn btn-sm btn-outline-danger delete_handover" type="button" data-id="<?php echo $row['id'] ?>">Excluir</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height:150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_handover').click(function(){
		uni_modal("Nova transferência","manage_handover.php","mid-large")
		
	})
	$('.edit_handover').click(function(){
		uni_modal("Gerenciar detalhes da transferência","manage_handover.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_handover').click(function(){
		_conf("Tem certeza de que deseja excluir esta transferência?","delete_handover",[$(this).attr('data-id')])
	})
	
	function delete_handover($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_handover',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dados excluídos com sucesso",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>