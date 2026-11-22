$ = jQuery;

function renderReviews() {
  const sort = $(`#sort-review`).val();
  const page = $(".fr-page.active").text();
  const post_id = $(".fr-post_id").val();
  $(`.fr-reviews`).css({
    opacity: 0.5,
  });

  $.ajax({
    url: frLocal.ajax_url,
    type: "POST",
    data: {
      action: "fr_get_reviews",
      sort: sort,
      page: page,
      post_id: post_id,
    },
    success: function (res) {
      if (res.success) {
        $(`.fr-reviews`).html(res.data.reviews);
        $(`.fr-reviews-footer`).html(res.data.pages);
      }
      $(`.fr-reviews`).css({
        opacity: 1,
      });
    },
    error: function (err) {
      frToast(err.responseText, "error");
      console.error(err.responseText);
    },
  });
}

function renderSummary() {
  $(".fr-summary").each(function () {
    let container = $(this);
    let postId = container.data("post-id");

    $.post(
      frLocal.ajax_url,
      {
        action: "fr_get_review_summary",
        post_id: postId,
      },
      function (res) {
        if (res.success) {
          let avg = parseFloat(res.data.avg) || 0;
          let count = res.data.count || 0;

          let starsHTML = renderStars(avg);

          let html = `
                    <div class="fr-summary-inner">
                        <div class="fr-rating">${avg.toFixed(2)}</div>
                        <div class="fr-summary-stars">${starsHTML}</div>
                        <div class="fr-count">Based on ${count} reviews</div>
                    </div>
                `;

          container.find(".fr-skeleton").remove();
          container.find(".fr-summary-content").html(html).fadeIn();
        }
      },
    );

    function renderStars(rating) {
      let html = "";
      for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
          html += '<i class="ri-star-fill"></i>';
        } else if (i - rating < 1) {
          html += '<i class="ri-star-half-fill"></i>';
        } else {
          html += '<i class="ri-star-line"></i>';
        }
      }
      return html;
    }
  });
}

$(document).ready(function () {
  $(document).on("click", "#show-review", function () {
    $(this).hide();
    $(`.fr-review-form form`).css({
      display: "grid",
    });
  });

  $(document).on("click", "#cancel-review", function () {
    $("#show-review").show();
    $(`.fr-review-form form`).hide();
  });

  $(document).on("change", "#sort-review", function () {
    renderReviews();
  });

  $(document).on("click", ".fr-page", function () {
    $(".fr-page").removeClass("active");
    $(this).addClass("active");
    renderReviews();
  });

  $(document).on("submit", `.fr-review-form form`, function (e) {
    e.preventDefault();
    const rating = $(this).find("#fr-rating").val();
    const feedback = $(this).find("#fr-feedback").val();
    const name = $(this).find("#fr-name").val();
    const email = $(this).find("#fr-email").val();
    const post_id = $(this).find("#fr-post_id").val();

    $(`.fr-review-form form`).find(".fr-loader").css({
      display: "inline-block",
    });

    $.ajax({
      url: frLocal.ajax_url,
      type: "POST",
      data: {
        action: "fr_submit_review",
        rating: rating,
        feedback: feedback,
        name: name,
        email: email,
        post_id: post_id,
      },
      success: function (res) {
        if (res.success) {
          frToast(res.data, "success");
        } else {
          frToast(res.data, "error");
        }
        $(`.fr-review-form form`).find(".fr-loader").css({
          display: "none",
        });
        renderReviews();
        renderSummary();
        $(`.fr-review-form form`)[0].reset();
      },
      error: function (err) {
        frToast(err.responseText, "error");
        console.error(err.responseText);
      },
    });
  });

  renderSummary();
});
