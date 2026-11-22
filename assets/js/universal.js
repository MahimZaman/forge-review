$ = jQuery;

function frToast(message, type = "info", duration = 3000) {
  // Create container if not exists
  if ($("#fr-toast-container").length === 0) {
    $("body").append('<div id="fr-toast-container"></div>');
  }

  const toastId = "fr-toast-" + Date.now();

  const icons = {
    success: "ri-checkbox-circle-line",
    error: "ri-close-circle-line",
    warning: "ri-error-warning-line",
    info: "ri-information-line",
  };

  const toastHTML = `
        <div class="fr-toast fr-toast-${type}" id="${toastId}">
            <div class="fr-toast-content">
                <i class="fr-toast-icon ri ${icons[type] || ""}"></i>
                <div class="fr-toast-message">${message}</div>
            </div>
            <span class="fr-toast-close">
                <i class="ri-close-line"></i>
            </span>
        </div>
    `;

  $("#fr-toast-container").append(toastHTML);

  const $toast = $("#" + toastId);

  // Show animation
  setTimeout(() => {
    $toast.addClass("show");
  }, 50);

  // Auto remove
  const autoRemove = setTimeout(() => {
    removeToast($toast);
  }, duration);

  // Close button
  $toast.find(".fr-toast-close").on("click", function () {
    clearTimeout(autoRemove);
    removeToast($toast);
  });

  function removeToast(el) {
    el.removeClass("show");
    setTimeout(() => {
      el.remove();
    }, 300);
  }
}
