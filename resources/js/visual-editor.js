/**
 * Storefront visual editor (admin). Metas: visual-editor, visual-editor-url, csrf-token.
 * Supports SiteSetting keys (group|key) and Eloquent targets (data-editable-table, id, column).
 */
function metaContent(name) {
  return (
    document.querySelector(`meta[name="${name}"]`)?.getAttribute("content")?.trim() ?? ""
  );
}

function showToast(message, isError = false) {
  let root = document.getElementById("ve-toast-root");
  if (!root) {
    root = document.createElement("div");
    root.id = "ve-toast-root";
    root.className = "ve-toast-root";
    document.body.appendChild(root);
  }
  const el = document.createElement("div");
  el.className = `ve-toast${isError ? " ve-toast--error" : ""}`;
  el.setAttribute("role", "status");
  el.textContent = message;
  root.appendChild(el);
  requestAnimationFrame(() => el.classList.add("ve-toast--show"));
  const t = window.setTimeout(() => {
    el.classList.remove("ve-toast--show");
    window.setTimeout(() => el.remove(), 280);
  }, 3200);
  el.addEventListener("click", () => {
    window.clearTimeout(t);
    el.remove();
  });
}

function normalizeText(el) {
  return (el.innerText ?? "").replace(/\r\n/g, "\n").trimEnd();
}

function ensureEditableKey(el) {
  let k = el.getAttribute("data-editable-key");
  if (k) return k;
  const table = el.getAttribute("data-editable-table");
  const id = el.getAttribute("data-editable-id");
  const col = el.getAttribute("data-editable-column");
  if (table && id != null && col) {
    k = `${table}|${id}|${col}`;
    el.setAttribute("data-editable-key", k);
  }
  return k;
}

function buildTextPayload(el, value) {
  const table = el.getAttribute("data-editable-table");
  const id = el.getAttribute("data-editable-id");
  const col = el.getAttribute("data-editable-column");
  if (table && id != null && id !== "" && col) {
    return { table, id: Number(id), column: col, value };
  }
  const key = el.getAttribute("data-editable-key");
  if (key) return { key, value };
  return null;
}

function buildImageFormFields(wrap) {
  const table = wrap.getAttribute("data-editable-table");
  const id = wrap.getAttribute("data-editable-id");
  const col = wrap.getAttribute("data-editable-column");
  if (table && id != null && id !== "" && col) {
    return { table, id: String(id), column: col };
  }
  const key = wrap.getAttribute("data-editable-image");
  if (key) return { key };
  return null;
}

function applyImageUrl(wrap, newUrl) {
  if (!newUrl || !wrap) return;
  wrap.querySelectorAll("img").forEach((img) => {
    img.src = newUrl;
  });
  if (wrap.classList.contains("ve-blob")) {
    const safe = newUrl.replace(/\\/g, "\\\\").replace(/"/g, '\\"');
    wrap.style.backgroundImage = `url("${safe}")`;
    wrap.style.backgroundSize = "cover";
    wrap.style.backgroundPosition = "center";
    wrap.classList.remove(
      "bg-surface-container-highest",
      "bg-primary-container",
      "bg-primary-container/20",
    );
  }
}

function teardownVisualEditorUi() {
  document.getElementById("nidan-ve-bar")?.remove();
  document.getElementById("ve-hidden-file-input")?.remove();
  document.querySelectorAll("[data-editable-image]").forEach((wrap) => {
    wrap.querySelector(".ve-img-edit")?.remove();
    wrap.classList.remove("ve-img-wrap--active");
  });
}

export function initVisualEditor() {
  if (metaContent("visual-editor") !== "1") return;

  const url = metaContent("visual-editor-url");
  if (!url) return;

  teardownVisualEditorUi();

  const csrf = metaContent("csrf-token");
  document.body.classList.add("has-visual-editor");

  /** @type {Map<HTMLElement, string>} */
  const dirtyByEl = new Map();
  /** @type {Map<HTMLElement, string>} */
  const originals = new Map();

  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.id = "ve-hidden-file-input";
  fileInput.accept = "image/jpeg,image/png,image/webp,image/gif,image/avif,image/svg+xml";
  fileInput.className = "sr-only";
  fileInput.setAttribute("tabindex", "-1");
  fileInput.setAttribute("aria-hidden", "true");

  /** @type {{ wrap: HTMLElement; fields: Record<string, string> } | null} */
  let pendingImage = null;

  const bar = document.createElement("div");
  bar.id = "nidan-ve-bar";
  bar.className = "ve-bar";
  bar.innerHTML = `
    <button type="button" class="ve-bar__toggle" aria-pressed="false">Edit mode</button>
    <button type="button" class="ve-bar__save" hidden>Save changes</button>
  `;
  document.body.appendChild(bar);
  document.body.appendChild(fileInput);

  const btnToggle = bar.querySelector(".ve-bar__toggle");
  const btnSave = bar.querySelector(".ve-bar__save");

  function refreshSaveVisibility() {
    btnSave.hidden = dirtyByEl.size === 0;
  }

  function captureOriginals() {
    originals.clear();
    document.querySelectorAll("[data-editable-key]").forEach((el) => {
      ensureEditableKey(el);
      if (!el.getAttribute("data-editable-key")) return;
      originals.set(el, normalizeText(el));
    });
  }

  function markDirty(el) {
    ensureEditableKey(el);
    if (!el.getAttribute("data-editable-key")) return;
    const orig = originals.get(el);
    const now = normalizeText(el);
    if (orig === undefined) return;
    if (now === orig) {
      dirtyByEl.delete(el);
    } else {
      dirtyByEl.set(el, now);
    }
    refreshSaveVisibility();
  }

  function setEditMode(on) {
    document.body.classList.toggle("ve-edit-mode", on);
    btnToggle.setAttribute("aria-pressed", String(on));
    btnToggle.textContent = on ? "Exit edit mode" : "Edit mode";

    document.querySelectorAll("[data-editable-key]").forEach((el) => {
      ensureEditableKey(el);
      if (!el.getAttribute("data-editable-key")) return;
      if (on) {
        el.setAttribute("contenteditable", "true");
        el.setAttribute("spellcheck", "false");
      } else {
        el.removeAttribute("contenteditable");
        el.removeAttribute("spellcheck");
      }
    });

    document.querySelectorAll("[data-editable-image]").forEach((wrap) => {
      wrap.classList.toggle("ve-img-wrap--active", on);
    });

    if (on) {
      captureOriginals();
    } else {
      dirtyByEl.clear();
      refreshSaveVisibility();
    }
  }

  btnToggle.addEventListener("click", () => {
    const next = !document.body.classList.contains("ve-edit-mode");
    setEditMode(next);
  });

  document.addEventListener(
    "input",
    (e) => {
      if (!document.body.classList.contains("ve-edit-mode")) return;
      const t = e.target;
      if (t?.getAttribute?.("data-editable-key")) markDirty(t);
    },
    true,
  );

  document.addEventListener(
    "blur",
    (e) => {
      if (!document.body.classList.contains("ve-edit-mode")) return;
      const t = e.target;
      if (t?.getAttribute?.("data-editable-key")) markDirty(t);
    },
    true,
  );

  async function postText(payload) {
    const res = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrf,
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "same-origin",
      body: JSON.stringify(payload),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const msg =
        data?.message ||
        data?.errors?.key?.[0] ||
        data?.errors?.value?.[0] ||
        data?.errors?.table?.[0] ||
        data?.errors?.column?.[0] ||
        `Save failed (${res.status})`;
      throw new Error(msg);
    }
    return data;
  }

  async function postImage(fields, file) {
    const fd = new FormData();
    Object.entries(fields).forEach(([k, v]) => fd.append(k, v));
    fd.append("image", file);
    const res = await fetch(url, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": csrf,
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "same-origin",
      body: fd,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const msg =
        data?.message ||
        data?.errors?.image?.[0] ||
        data?.errors?.key?.[0] ||
        data?.errors?.table?.[0] ||
        `Upload failed (${res.status})`;
      throw new Error(msg);
    }
    return data;
  }

  btnSave.addEventListener("click", async () => {
    if (dirtyByEl.size === 0) return;
    btnSave.disabled = true;
    showToast("Saving…");
    const entries = [...dirtyByEl.entries()];
    try {
      for (const [el, value] of entries) {
        const payload = buildTextPayload(el, value);
        if (!payload) continue;
        await postText(payload);
        const key = el.getAttribute("data-editable-key");
        if (key === "home|hero_image_alt") {
          document.querySelector("img[data-ve-hero-img]")?.setAttribute("alt", value);
        }
      }
      showToast("Saved.");
      captureOriginals();
      dirtyByEl.clear();
      refreshSaveVisibility();
    } catch (err) {
      showToast(err.message || "Save failed.", true);
    } finally {
      btnSave.disabled = false;
    }
  });

  fileInput.addEventListener("change", async () => {
    const file = fileInput.files?.[0];
    const ctx = pendingImage;
    pendingImage = null;
    fileInput.value = "";
    if (!file || !ctx) return;

    showToast("Saving…");
    try {
      const data = await postImage(ctx.fields, file);
      const newUrl = data?.url;
      if (newUrl) {
        applyImageUrl(ctx.wrap, newUrl);
      }
      showToast("Image updated.");
    } catch (err) {
      showToast(err.message || "Upload failed.", true);
    }
  });

  document.querySelectorAll("[data-editable-image]").forEach((wrap) => {
    const fields = buildImageFormFields(wrap);
    if (!fields) return;

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "ve-img-edit";
    btn.setAttribute("aria-label", "Change image");
    btn.innerHTML =
      '<span class="material-symbols-outlined" data-icon="edit">edit</span>';
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (!document.body.classList.contains("ve-edit-mode")) return;
      pendingImage = { wrap, fields };
      fileInput.click();
    });
    wrap.appendChild(btn);
  });

  if (document.body.classList.contains("ve-edit-mode")) {
    setEditMode(true);
  }
}
