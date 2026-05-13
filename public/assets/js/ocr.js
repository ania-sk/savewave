document.addEventListener("DOMContentLoaded", () => {
  const elements = {
    input: document.getElementById("receipt-upload"),
    canvas: document.getElementById("resize-canvas"),
    status: document.getElementById("ocr-status"),
    loader: document.getElementById("ocr-loader"),
    loaderText: document.getElementById("ocr-loader-text"),
  };

  const MAX_SIZE = 1500;

  init();

  function init() {
    elements.input.addEventListener("change", handleFileSelect);
  }

  async function handleFileSelect(event) {
    const file = event.target.files[0];

    if (!file) return;

    try {
      showLoader("Preparing image...");

      const resizedBlob = await resizeImage(file);

      showLoader("Analyzing receipt with OCR...");

      const response = await uploadReceipt(resizedBlob);

      showSuccess("Receipt has been analyzed successfully.");

      console.log("OCR RESULT:", response.text);

      console.log("EXTRACTED DATA:", response.extracted);
      // here
      // open modal
      // parse categories
      // autofill expense form
    } catch (error) {
      console.error(error);
      showError(error.message || "An OCR error occurred.");
    } finally {
      hideLoader();
      elements.input.value = "";
    }
  }

  function resizeImage(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();

      reader.onload = (event) => {
        const img = new Image();

        img.onload = () => {
          let width = img.width;
          let height = img.height;

          if (width > height && width > MAX_SIZE) {
            height *= MAX_SIZE / width;
            width = MAX_SIZE;
          }

          if (height > width && height > MAX_SIZE) {
            width *= MAX_SIZE / height;
            height = MAX_SIZE;
          }

          const canvas = elements.canvas;
          const ctx = canvas.getContext("2d");

          canvas.width = width;
          canvas.height = height;

          ctx.drawImage(img, 0, 0, width, height);

          canvas.toBlob(
            (blob) => {
              if (!blob) {
                reject(new Error("Failed to process image."));
                return;
              }

              resolve(blob);
            },
            "image/jpeg",
            0.8,
          );
        };

        img.onerror = () => {
          reject(new Error("Failed to load image."));
        };

        img.src = event.target.result;
      };

      reader.readAsDataURL(file);
    });
  }

  async function uploadReceipt(blob) {
    const formData = new FormData();

    formData.append("receipt", blob, "receipt.jpg");

    const csrfToken = document.querySelector('input[name="token"]')?.value;

    if (csrfToken) {
      formData.append("token", csrfToken);
    }

    const response = await fetch("/uploadRecipe", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (!response.ok || data.status === "error") {
      throw new Error(data.message || "Failed to analyze receipt.");
    }

    return data;
  }

  function showLoader(message) {
    elements.loader.classList.remove("hidden");
    elements.loaderText.textContent = message;

    hideStatus();
  }

  function hideLoader() {
    elements.loader.classList.add("hidden");
  }

  function showSuccess(message) {
    showStatus(message, "success");
  }

  function showError(message) {
    showStatus(message, "error");
  }

  function showStatus(message, type) {
    elements.status.textContent = message;
    elements.status.className = `ocr-status ${type}`;
    elements.status.classList.remove("hidden");
  }

  function hideStatus() {
    elements.status.classList.add("hidden");
  }
});
