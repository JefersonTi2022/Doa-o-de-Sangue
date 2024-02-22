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
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_request">
					<i class="fa fa-plus"></i> Novo
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Data</th>
									<th class="">Código de referência</th>
									<th class="">Nome do Paciente</th>
									<th class="">Grupo Sanguíneo</th>
									<th class="">Informação</th>
									<th class="">Situação</th>
									<th class="text-center">Editar/Excluir</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$requests = $conn->query("SELECT * FROM requests order by date(date_created) desc ");
								while($row=$requests->fetch_assoc()):
									
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
										 <p>Volume Necessário: <b><?php echo  ($row['volume'] / 1000).' L' ?></b></p>
										 <p>Nome do Médico: <b><?php echo ucwords($row['physician_name']) ?></b></p>
									</td>
									<td class=" text-center">
										<?php if($row['status'] == 0): ?>
											<span class="badge badge-primary">Pendente</span>
										<?php else: ?>
											<span class="badge badge-success">Aprovado</span>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_request" type="button" data-id="<?php echo $row['id'] ?>" >Editar</button>
										<button class="btn btn-sm btn-outline-danger delete_request" type="button" data-id="<?php echo $row['id'] ?>">Excluir</button>
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
	
	$('#new_request').click(function(){
		uni_modal("Nova Solicitação","manage_request.php","mid-large")
		
	})
	$('.edit_request').click(function(){
		uni_modal("Gerenciar detalhes da solicitação","manage_request.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_request').click(function(){
		_conf("Tem certeza de que deseja excluir esta solicitação?","delete_request",[$(this).attr('data-id')])
	})
	
	function delete_request($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_request',
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