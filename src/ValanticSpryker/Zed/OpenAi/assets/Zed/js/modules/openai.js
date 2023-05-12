$(document).ready(function () {
    // ToDo: provide twig components if possible in backoffice to get more flexibility

    const currentDomain = window.location.protocol + "//" + window.location.host;

    // Find the form element with the name containing "description"
    const descriptionElements = document.querySelectorAll('textarea[name*="description"]');

    // Loop through each description element
    for (var i = 0; i < descriptionElements.length; i++) {
        var descriptionElement = descriptionElements[i];

        var modal = `
        <div id="myModal" class="modal">
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Edit Text</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

                <div id="spinner" style="display: block;">
                      <i class="fa fa-spinner fa-spin"></i> <span id="spinner-text">Collecting prompts...</span>
                </div>

              <!-- Modal body -->
              <div class="modal-body" id="modal-body" style="display: none;">
                <div>Assign Prompt:</div> <select id="openapi-choices" class="form-control"></select>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div>`;

        // Create new button with edit icon using fontawesome
        var newButton = $("<button disabled class='btn btn-xs btn-outline openai-generate' style='border-top-right-radius: 0.25rem;border-bottom-right-radius: 0.25rem;' data-openai-event='"+descriptionElement.id+"_event''>Generate</button>" +
            "<button class='btn btn-xs rounded-right openai-edit' style='border-bottom-left-radius: 0.25rem;border-top-left-radius: 0.25rem;' data-openai-event='"+descriptionElement.id+"_event''><i class='fas fa-edit'></i></button>"+modal);

        // Insert new button after description element
        $(descriptionElement).after(newButton);
    }

    const spinner = document.getElementById('spinner');
    const spinnerText = document.getElementById('spinner-text');
    const modalBody = document.getElementById('modal-body');
    const selectBox = document.querySelector('#openapi-choices');
    const modalJQuery = $("#myModal");

    unlockUiGenerateButtons();

    function unlockUiGenerateButtons() {
        document.querySelector(".openai-generate").setAttribute("disabled", true);

        fetch(currentDomain + '/open-ai/ajax/get-open-ai-prompt-to-event-collection')
            .then(response => response.json())
            .then(data => {
                for (var i = 0; i < data.length; i++) {
                    const element = document.querySelector(".openai-generate[data-openai-event='"+data[i].event+"']");
                    if (element) {
                        element.removeAttribute("disabled");
                    }

                    console.log();
                }
            })
            .catch(error => {
                console.error(error)
            });
    }

    $(".openai-generate").on("click", function(event) {
        event.preventDefault();

        let nameInput =event.target.closest('.ibox').querySelector('input[name*="name"]');
        let skuInput = event.target.closest('form').querySelector('input[name*="sku"]');
        let descriptionInput = event.target.closest('.form-group ').querySelector('textarea[name*="description"]');
        let eventName = $(this).data("openai-event");

        modalJQuery.modal("show");
        loading("Executing Prompt...");
        executePrompt(eventName, {title: nameInput.value, sku: skuInput.value}, function(data) {
            selectBox.innerHTML = data.choices.map(completionChoice => `<option title='${completionChoice.text}' value='${completionChoice.text}'>${completionChoice.text.substring(0,70)}</option>`).join('');
            loaded();
            modalJQuery.find(".modal-footer button").unbind("click");
            modalJQuery.find(".modal-footer button").on("click", function(event) {
                descriptionInput.value = selectBox.value;
                modalJQuery.modal("hide");
            });

        });
    });

    function executePrompt(eventName, context, successCallback) {
        const endpoint = currentDomain + '/open-ai/ajax/execute-prompt';

        const data = {
            event: eventName,
            context: context
        };

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        };

        fetch(endpoint, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                successCallback(data);
                console.log(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function updateModalChoicesWithPromptCollection() {
        fetch(currentDomain + '/open-ai/ajax/get-open-ai-prompt-collection')
            .then(response => response.json())
            .then(data => {
                loaded();
                selectBox.innerHTML = data.map(prompt => `<option value="${prompt.id_openai_prompt}">${prompt.name}</option>`).join('');
            })
            .catch(error => {
                loaded();
                console.error(error)
            });

    }

    function assignPromptToEvent(prompt, event) {
        const endpoint = currentDomain + '/open-ai/ajax/assign-event-to-prompt';
        const data = {
            prompt: prompt,
            event: event
        };

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        };

        fetch(endpoint, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                modalJQuery.modal("hide");
                unlockUiGenerateButtons();
                console.log(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }


    // Add a click event listener to the edit button
    $(".openai-edit").on("click", function(event) {
        event.preventDefault();
        loading("Collecting Prompts...");
        updateModalChoicesWithPromptCollection();

        modalJQuery.modal("show");
        modalJQuery.find(".modal-footer button").unbind("click");
        modalJQuery.find(".modal-footer button").on("click", function(event) {
            loading("Assigning Prompt...");
            assignPromptToEvent(selectBox.value, $(this).data("openai-event"));
        }.bind(this));
    });

    function loading(text) {
        spinner.style.display = 'block';
        spinnerText.innerText = text;
        modalBody.style.display = 'none';
    }

    function loaded() {
        spinner.style.display = 'none';
        spinnerText.value = '';
        modalBody.style.display = 'block';
    }
});
