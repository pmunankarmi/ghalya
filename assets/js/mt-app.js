(function () {
  "use strict";

  var applicationCookieName = "mt_ghalya_application";
  var applicationStorageKey = "mt-ghalya-application";
  var applicationState = readApplicationState();

  // Read the saved multi-step application from a cookie or local fallback.
  function readApplicationState() {
    var cookieMatch = document.cookie.match(
      new RegExp("(?:^|; )" + applicationCookieName + "=([^;]*)"),
    );
    var savedValue = cookieMatch ? decodeURIComponent(cookieMatch[1]) : "";

    if (!savedValue) {
      try {
        savedValue = window.localStorage.getItem(applicationStorageKey) || "";
      } catch (error) {
        savedValue = "";
      }
    }

    try {
      return savedValue ? JSON.parse(savedValue) : {};
    } catch (error) {
      return {};
    }
  }

  // Save to a cookie on hosted pages and local storage for direct file previews.
  function writeApplicationState() {
    var savedValue = JSON.stringify(applicationState);

    document.cookie =
      applicationCookieName +
      "=" +
      encodeURIComponent(savedValue) +
      "; max-age=2592000; path=/; SameSite=Lax";

    try {
      window.localStorage.setItem(applicationStorageKey, savedValue);
    } catch (error) {
      // Cookie storage remains available when local storage is restricted.
    }
  }

  // Remove both storage copies after a confirmed server-side submission.
  function clearApplicationState() {
    applicationState = {};
    document.cookie =
      applicationCookieName +
      "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax";

    try {
      window.localStorage.removeItem(applicationStorageKey);
    } catch (error) {
      // The expired cookie is enough when local storage is unavailable.
    }
  }

  function getApplicationStepKey() {
    var stepClass = Array.from(document.body.classList).find(
      function (className) {
        return className.indexOf("mt-step-") === 0;
      },
    );

    return document.documentElement.lang + ":" + (stepClass || "application");
  }

  function collectFormValues(form) {
    var values = {};

    form
      .querySelectorAll("input[name], select[name], textarea[name]")
      .forEach(function (control) {
        var name = control.name;

        // This transport-only field is generated from applicationState below.
        if (name === "application_data") {
          return;
        }

        if (control.type === "radio") {
          if (!(name in values)) {
            values[name] = "";
          }

          if (control.checked) {
            values[name] = control.value;
          }
        } else if (control.type === "checkbox") {
          if (!Array.isArray(values[name])) {
            values[name] = [];
          }

          if (control.checked) {
            values[name].push(control.value);
          }
        } else {
          values[name] = control.value;
        }
      });

    return values;
  }

  function saveApplicationForm(form) {
    applicationState[getApplicationStepKey()] = collectFormValues(form);
    writeApplicationState();
  }

  function restoreApplicationForm(form) {
    var values = applicationState[getApplicationStepKey()];
    var addLinkButton = form.querySelector("[data-mt-add-link]");

    if (!values) {
      return;
    }

    // Recreate any extra portfolio fields before restoring their values.
    Object.keys(values)
      .filter(function (name) {
        return /^brand_content_url_\d+$/.test(name);
      })
      .sort()
      .forEach(function (name) {
        if (
          addLinkButton &&
          !form.querySelector('[name="' + name + '"]') &&
          typeof addLinkButton.mtAddPortfolioField === "function"
        ) {
          addLinkButton.mtAddPortfolioField(false, false);
        }
      });

    form
      .querySelectorAll("input[name], select[name], textarea[name]")
      .forEach(function (control) {
        var savedValue = values[control.name];

        if (typeof savedValue === "undefined") {
          return;
        }

        if (control.type === "radio") {
          control.checked = savedValue === control.value;
        } else if (control.type === "checkbox") {
          control.checked =
            Array.isArray(savedValue) && savedValue.indexOf(control.value) >= 0;
        } else {
          control.value = savedValue;
        }
      });
  }

  // The server adds this flag only after PHP has sent the application.
  if (new URLSearchParams(window.location.search).get("submitted") === "1") {
    clearApplicationState();
  }

  // Keep the footer year current without editing each language version.
  document.querySelectorAll("[data-mt-year]").forEach(function (node) {
    node.textContent = new Date().getFullYear();
  });

  // Reveal key page sections as they enter the viewport.
  if (typeof window.AOS !== "undefined") {
    window.AOS.init({
      duration: 650,
      easing: "ease-out-cubic",
      offset: 48,
      once: true,
      disable: function () {
        return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      },
    });

    // Recalculate positions after fonts and images have finished loading.
    window.addEventListener("load", function () {
      window.AOS.refresh();
    });
  }

  // Validate each application step before opening the next page.
  if (
    typeof window.jQuery !== "undefined" &&
    typeof window.jQuery.fn.validate === "function"
  ) {
    var $ = window.jQuery;
    var isArabic = document.documentElement.lang === "ar";

    if (isArabic) {
      $.extend($.validator.messages, {
        required: "هذا الحقل مطلوب.",
        email: "يرجى إدخال بريد إلكتروني صحيح.",
        url: "يرجى إدخال رابط صحيح يبدأ بـ http:// أو https://.",
        minlength: $.validator.format("يرجى إدخال {0} أحرف على الأقل."),
      });
    }

    $(".mt-js-form").each(function () {
      $(this).validate({
        errorClass: "mt-field-error",
        validClass: "mt-field-valid",
        errorElement: "span",
        focusInvalid: false,
        highlight: function (element) {
          $(element).addClass("mt-is-invalid");
        },
        unhighlight: function (element) {
          $(element).removeClass("mt-is-invalid");
        },
        errorPlacement: function (error, element) {
          var agreement = element.closest(".mt-agree");
          var choices = element.closest(".mt-chip-row");

          if (agreement.length) {
            error.insertAfter(agreement);
          } else if (choices.length) {
            error.insertAfter(choices);
          } else {
            error.insertAfter(element);
          }
        },
        submitHandler: function (form) {
          saveApplicationForm(form);

          if (form.hasAttribute("data-mt-sendmail")) {
            var applicationField = form.querySelector(
              '[name="application_data"]',
            );

            if (applicationField) {
              applicationField.value = JSON.stringify(applicationState);
            }

            // Native submission avoids running the jQuery handler a second time.
            form.submit();
            return;
          }

          var nextPage =
            form.getAttribute("data-mt-next") || form.getAttribute("action");

          if (nextPage) {
            window.location.href = nextPage;
          }
        },
      });
    });
  }

  // Add optional portfolio URL fields without reloading the Work page.
  document.querySelectorAll("[data-mt-add-link]").forEach(function (button) {
    var container = document.getElementById(
      button.getAttribute("aria-controls"),
    );
    var linkCount = 1;

    if (!container) {
      return;
    }

    function addPortfolioField(shouldFocus, shouldSave) {
      linkCount += 1;

      var field = document.createElement("div");
      var label = document.createElement("label");
      var input = document.createElement("input");
      var inputId = "brand-content-extra-" + linkCount;

      field.className = "mt-field mt-extra-link-field";
      label.className = "mt-label";
      label.htmlFor = inputId;
      label.textContent = button.getAttribute("data-mt-label");

      input.className = "mt-input";
      input.id = inputId;
      input.name = "brand_content_url_" + linkCount;
      input.required = true;
      input.type = "url";
      input.placeholder = button.getAttribute("data-mt-placeholder");

      field.appendChild(label);
      field.appendChild(input);
      container.appendChild(field);

      // Register the additional URL with the active validator.
      if (
        typeof window.jQuery !== "undefined" &&
        typeof window.jQuery.fn.validate === "function"
      ) {
        window.jQuery(input).rules("add", {
          required: true,
          url: true,
        });
      }

      if (shouldSave) {
        saveApplicationForm(button.closest("form"));
      }

      if (shouldFocus) {
        input.focus();
      }
    }

    button.mtAddPortfolioField = addPortfolioField;

    button.addEventListener("click", function () {
      addPortfolioField(true, true);
    });
  });

  // Restore saved values, then keep them current as the user edits each step.
  document.querySelectorAll(".mt-js-form").forEach(function (form) {
    restoreApplicationForm(form);

    form.addEventListener("input", function () {
      saveApplicationForm(form);
    });

    form.addEventListener("change", function () {
      saveApplicationForm(form);
    });

    form.addEventListener("submit", function () {
      saveApplicationForm(form);
    });
  });

  // Turn each partner strip into a touch-friendly logo carousel.
  if (typeof window.Swiper !== "undefined") {
    document.querySelectorAll(".mt-partner-swiper").forEach(function (slider) {
      var reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
      ).matches;

      new window.Swiper(slider, {
        slidesPerView: 4.35,
        spaceBetween: 8,
        grabCursor: true,
        loop: true,
        speed: 850,
        watchOverflow: false,
        autoplay: reduceMotion
          ? false
          : {
              delay: 1800,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            },
        freeMode: {
          enabled: true,
          momentumRatio: 0.65,
        },
        breakpoints: {
          576: {
            slidesPerView: 4.6,
            spaceBetween: 12,
          },
          992: {
            slidesPerView: 5,
            spaceBetween: 16,
            freeMode: false,
          },
        },
      });
    });
  }
})();
