@php
$colour_class = "";
@endphp

@extends('layouts.alternative')


@section('canonical', '')


@section('additional_head')

@endsection

@section('content')

<div class="container-fluid">
	
	@include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => 'Заказы'],
        ['url' => '/suppliers', 'label' => 'Строители'],
        ['url' => '/messages', 'label' => 'Мои переписки'],
        ['url' => '/profile', 'label' => 'Мои данные'],
    ]])
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div id="bulkActionContainer" style="display: none;">
							<a href="#" id="bulkDeleteBtn" class="text-danger me-3">
								<i class="fas fa-trash-alt"></i> Удалить
							</a>
							<a href="#" id="supportBtn" class="text-primary" style="display: none;">
								<i class="fas fa-headset"></i> Поддержка
							</a>
						</div>
						<!-- Add any other buttons or elements you want here -->
					</div>
                    <div class="dropdown-item text-warning">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="d-none d-md-inline">
                                <strong>Пожалуйста имейте ввиду</strong>, что файлы доступны для скачивания только в течении 30 дней после создания заказа.
                            </span>
                            <span class="d-md-none">
                                <strong>Важно</strong> 
                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" 
                                   title="Файлы доступны для скачивания только в течении 30 дней после создания заказа"></i>
                            </span>
                        </div>
					</div>
					<div class="table-responsive">
						@if($projects->isNotEmpty())

						<table class="table table-sm table-responsive-lg table-bordered border solid text-black min-h-150">
							<thead>
								<tr>
									<th class="align-middle text-center checkbox-column">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="checkAll">
											<label class="custom-control-label" for="checkAll"></label>
										</div>
									</th>
									<th class="align-middle text-center order-column">Заказ</th>
									<th class="align-middle text-center" style="width: 80px;"></th>
									<th class="align-middle text-center">Ваш проект и конфигурация</th>
									<th class="align-middle text-center">Документы</th>
									<!--		<th class="align-middle text-center minw200">Исполнитель</th>  comment this out temporarily -->
									<th class="align-middle text-center minw200">Цена</th>
								</tr>
							</thead>
							<tbody id="orders">
							@foreach($projects as $project)
                            @if($new_order)
                                @php
                                    $new_order = base64_decode($new_order);
                                    $colour_class = $new_order == $project['id'] ? ($payment_status == 'success' ? 'bg-success-light' : ($payment_status == 'error' ? 'bg-danger-light' : '')) : '';
                                @endphp
                            @endif
								<tr class="{{ $colour_class }}">
									<td class="py-2 text-center checkbox-column">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="customCheckBox{{ $project['id'] }}" required="">
											<label class="custom-control-label" for="customCheckBox{{ $project['id'] }}"></label>
										</div>
									</td>
									<td class="py-2 order-column">
											<strong>{{ $project['id'] }}</strong>
										   <br> от <small>{{ $project['created_at'] }}</small>
									</td>
									<td class="py-2 text-center">
										@if($project['thumbnail'])
											<img src="{{ $project['thumbnail'] }}" alt="Thumbnail" class="img-fluid" style="max-width: 90px; max-height: 70px;">
										@else
											<span class="text-muted">Нет фото</span>
										@endif
									</td>
									<td class="py-2 text-center">
										{{ $project['title'] }} <br> 
										<small>{{ $project['configuration'] }}</small>
									</td>
                                    <td class="py-2 text-center vertical-align-middle">
										<div class="dropdown ms-auto text-centre">
										@if($project['filepath'] && ( $project['payment_status'] == 'success' || $project['is_example'] ))
											<div class="btn-link" data-bs-toggle="dropdown">
												<i class="fas fa-download fa-lg" style="color: #007bff;"></i>
											</div>
											<div class="dropdown-menu dropdown-menu-orders">
												<a class="dropdown-item" href="{{ $project['filepath'] }}" download>
													<i class="fas fa-file-excel fa-lg" style="color: #217346;"></i> Скачать xlsx
												</a>
                                                @if($project['order_type'] != 'foundation')
												<a class="dropdown-item" href="{{ route('orders.view', $project['id']) }}" target="_blank">
													<i class="fas fa-eye fa-lg" style="color: #17a2b8;"></i> Смотреть
												</a>
                                                @endif
											</div>
										@elseif($project['payment_link'] && $project['payment_status'] != 'success' && !$project['is_example'])
                                        <a href="{{ $project['payment_link'] }}" class="btn btn-sm btn-success">Оплатить</a>
                                        @else
                                        <i class="fas fa-spinner fa-spin"></i>
										@endif
										</div>
									</td>
									@if(!$project['is_example'] && false) // TODO: remove this
                                    <td class="py-2 text-center vertical-align-middle">
										<button class="btn btn-sm btn-primary assign-executor" data-project-id="{{ $project['id'] }}">Подобрать</button>
									</td>
                                    @endif
									<td class="py-2 text-center vertical-align-middle">
                                        @if($project['filepath'] && ( $project['payment_status'] == 'success' && !$project['is_example'] && $project['payment_amount'] > 0 ))
										<div>{{ $project['payment_amount'] }} руб.</div>
										<a href="{{ route('general.receipt', $project['payment_reference']) }}" class="receipt-link">
											<i class="fas fa-receipt fa-lg"></i>
											<div class="receipt-caption">чек</div>
										</a>
                                        @endif
									</td>
								</tr>
                                @if($new_order == $project['id'])
                                    <tr class="{{ $colour_class }}">
                                    @if($payment_status == 'success')
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 0; padding: 0;">
                                            <strong>Новый заказ успешно создан!</strong> Пожалуйста, дождитесь выполнения заказа перед скачиванием.
                                        </div>
                                    </td>
                                    @elseif($payment_status == 'error')
                                    <td colspan="7" class="text-center"> 
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 0; padding: 0;">
                                            <strong>Ошибка при оплате</strong> Пожалуйста, попробуйте еще раз.
                                        </div> 
                                    </td>
                                    @endif
                                    </tr>
                                @endif
							@endforeach
							</tbody>
						</table>
						@else
							<div class="alert alert-info" role="alert">
								Заказов нет
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- executors -->
<div class="modal fade" id="executorModal" tabindex="-1" role="dialog" aria-labelledby="executorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="executorModalLabel">Выбрать исполнителя</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="region-select">Выберите регион строительства:</label>
                    <select id="region-select" class="form-control">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <button id="confirm-region" class="btn btn-primary mb-3">Подтвердить</button>
                <div id="executorList" class="row"></div>
                <div id="selected-executor" class="text-center" style="display: none;">
                    <img id="selected-executor-img" src="" alt="Executor" class="rounded-circle mb-3" width="150">
                    <h4 id="selected-executor-name"></h4>
                    <textarea id="message-to-executor" class="form-control mb-3" rows="4" placeholder="Введите текст для вашего исполнителя"></textarea>
                    <button id="send-message" class="btn btn-primary">Отправить сообщение</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- executors -->
<!-- Disclaimer Modal -->
<div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-labelledby="disclaimerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disclaimerModalLabel">Важное уведомление</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Внимание! Все коммуникации вне этого веб-сайта будут наказываться по всей строгости закона Российской Федерации. Во избежание проблем, пожалуйста, осуществляйте все взаимодействия через наш сайт.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="acceptDisclaimer">Принять и продолжить</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    const checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
    const checkAll = document.getElementById('checkAll');
    const bulkActionContainer = document.getElementById('bulkActionContainer');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const supportBtn = document.getElementById('supportBtn');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.custom-control-input:checked:not(#checkAll)');
        bulkActionContainer.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
        supportBtn.style.display = checkedBoxes.length === 1 ? 'inline-block' : 'none';
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    checkAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    bulkDeleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Вы уверены, что хотите удалить выбранные заказы?')) {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.id.replace('customCheckBox', ''));
            // Here you would typically send an AJAX request to delete the orders
            console.log('Deleting orders:', selectedIds);
            // After successful deletion, remove the rows from the table
            selectedIds.forEach(id => {
                document.querySelector(`tr:has(#customCheckBox${id})`).remove();
            });
            updateBulkActions();
        }
    });

    supportBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedId = document.querySelector('.custom-control-input:checked:not(#checkAll)').id.replace('customCheckBox', '');
        showSupportModal(selectedId);
    });

    function showSupportModal(orderId) {
        // Create and show the support modal
        const modal = `
        <div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supportModalLabel">Отправить сообщение в поддержку</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea id="supportMessage" class="form-control" rows="4" placeholder="Введите ваше сообщение"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="sendSupportMessage">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modal);
        $('#supportModal').modal('show');

        document.getElementById('sendSupportMessage').addEventListener('click', function() {
            const message = document.getElementById('supportMessage').value;
            // Here you would typically send an AJAX request with the message and orderId
            console.log('Sending support message for order:', orderId, 'Message:', message);
            $('#supportModal').modal('hide');
            alert('Спасибо за ваше сообщение. Мы ответим в течение 24 часов. Пока вы ждете, почему бы не просмотреть наши планы или исполнителей в вашем районе?');
        });
    }

    // Add this to automatically dismiss the alert after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endpush

<style>
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .receipt-link {
        display: inline-block;
        text-decoration: none;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .receipt-link:hover {
        color: #0056b3;
    }

    .receipt-caption {
        font-size: 0.7rem;
        margin-top: 2px;
    }
</style>