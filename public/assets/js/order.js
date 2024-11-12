// public/js/order.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('Order script loaded at: ' + new Date().toISOString());

    // Assign executor button click handler
    document.querySelectorAll('.assign-executor').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.dataset.projectId;
            showDisclaimerModal(projectId);
        });
    });

    // Accept disclaimer button click handler
    document.getElementById('acceptDisclaimer').addEventListener('click', function() {
        const projectId = this.dataset.projectId;
        hideDisclaimerModal();
        loadExecutors(projectId);
    });

    // Select executor button click handler (using event delegation)
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('select-executor')) {
            const executorId = event.target.dataset.executorId;
            const projectId = event.target.dataset.projectId;
            assignExecutor(executorId, projectId);
        }
    });
});

function showDisclaimerModal(projectId) {
    const modal = document.getElementById('disclaimerModal');
    const acceptButton = document.getElementById('acceptDisclaimer');
    acceptButton.dataset.projectId = projectId;
    new bootstrap.Modal(modal).show();
}

function hideDisclaimerModal() {
    const modal = document.getElementById('disclaimerModal');
    bootstrap.Modal.getInstance(modal).hide();
}

function loadExecutors(projectId) {
    fetch(`/api/projects/${projectId}/available-executors`)
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            const executorList = document.getElementById('executorList');
            
            if (Array.isArray(data) && data.length > 0) {
                const executorHtml = data.map(executor => `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card contact-bx">
                            <div class="card-body">
                                <div class="media">
                                    <div class="image-bx me-3">
                                        <img src="${executor.profile_picture_url || '/images/users/default.jpg'}" alt="${executor.company_name}" class="rounded-circle" width="90">
                                        <span class="active"></span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="fs-18 font-w600 mb-0">${executor.company_name}</h6>
                                        <p class="fs-14">${executor.type_of_organisation || 'N/A'}</p>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-primary select-executor" data-executor-id="${executor.id}" data-project-id="${projectId}">Запросить</button>
                                            <a href="/executor-profile/${executor.id}" class="btn btn-sm btn-outline-primary" target="_blank">Профиль</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
                executorList.innerHTML = executorHtml;
            } else if (Array.isArray(data) && data.length === 0) {
                executorList.innerHTML = '<p>Нет доступных исполнителей</p>';
            } else {
                console.error('Unexpected response format:', data);
                executorList.innerHTML = '<p>Ошибка: Неверный формат данных</p>';
            }
            
            new bootstrap.Modal(document.getElementById('executorModal')).show();
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Ошибка при загрузке списка исполнителей');
        });
}

function assignExecutor(executorId, projectId) {
    fetch(`/api/projects/${projectId}/assign-executor`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ executor_id: executorId })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('executorModal')).hide();
        alert('Исполнитель успешно назначен');
        location.reload();
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Ошибка при назначении исполнителя');
    });
}