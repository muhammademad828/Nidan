/**
 * Nidan storefront — vanilla ES6+.
 * Toggles classes only (.is-scrolled, .fade-in, .is-paused, .nav-drawer-open, etc.).
 * Does not set element.style for position, margin, padding, or transform on layout.
 */
import "../css/nidan-vanilla.css";
import { initVisualEditor } from "./visual-editor.js";

const prefersReducedMotion = () =>
  window.matchMedia?.("(prefers-reduced-motion: reduce)")?.matches ?? false;

function metaContent(name) {
  return (
    document.querySelector(`meta[name="${name}"]`)?.getAttribute("content")?.trim() ??
    ""
  );
}

function csrfToken() {
  return metaContent("csrf-token");
}

function syncCartBadges(data, silent = false) {
  if (!data || typeof data.item_count !== "number") return;
  document.querySelectorAll("[data-cart-count]").forEach((el) => {
    el.textContent = String(data.item_count);
  });
  if (!silent) {
    document.dispatchEvent(new CustomEvent("nidan:cart-updated", { detail: data }));
  }
}

function homePathname() {
  const raw = metaContent("home-url");
  if (!raw) return "/";
  try {
    const u = new URL(raw, window.location.origin);
    const p = u.pathname.replace(/\/$/, "");
    return p === "" ? "/" : p;
  } catch {
    return "/";
  }
}

function isHomePath() {
  const p = window.location.pathname.replace(/\/$/, "") || "/";
  const h = homePathname();
  return p === h;
}

function closeOtherOverlays(except) {
  if (except !== "search") {
    const so = document.getElementById("site-search-overlay");
    const sb = document.querySelector("[data-site-search-open]");
    if (so?.classList.contains("is-open")) {
      so.classList.remove("is-open");
      so.setAttribute("aria-hidden", "true");
      sb?.setAttribute("aria-expanded", "false");
      document.body.classList.remove("site-search-open");
    }
  }
  if (except !== "cart") {
    const mc = document.getElementById("mini-cart");
    const ob = document.querySelector("[data-mini-cart-open]");
    if (mc?.classList.contains("is-open")) {
      mc.classList.remove("is-open");
      mc.setAttribute("aria-hidden", "true");
      ob?.setAttribute("aria-expanded", "false");
      document.body.classList.remove("mini-cart-open");
    }
  }
}

function initStickyHeader() {
  const header = document.querySelector("#site-header");
  if (!header) return;

  const onScroll = () => {
    const y = window.scrollY || document.documentElement.scrollTop;
    header.classList.toggle("is-scrolled", y > 48);
  };

  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
}

function initMobileNav() {
  const openBtn = document.querySelector("[data-nav-open]");
  const closeBtn = document.querySelector("[data-nav-close]");
  const drawer = document.querySelector("[data-nav-drawer]");
  if (!drawer || !openBtn) return;

  const focusable = () =>
    drawer.querySelectorAll(
      'a[href], button:not([disabled]), input:not([disabled])',
    );

  const setOpen = (open) => {
    drawer.classList.toggle("is-open", open);
    drawer.setAttribute("aria-hidden", String(!open));
    openBtn.setAttribute("aria-expanded", String(open));
    document.body.classList.toggle("nav-drawer-open", open);
    if (open) {
      const first = focusable()[0];
      first?.focus?.();
    }
  };

  openBtn.addEventListener("click", () => setOpen(true));
  closeBtn?.addEventListener("click", () => setOpen(false));

  drawer.addEventListener("click", (e) => {
    const t = e.target;
    if (t === drawer || t?.closest?.("[data-nav-drawer-dismiss]")) {
      setOpen(false);
    }
  });

  drawer.querySelectorAll("a[href]").forEach((a) => {
    a.addEventListener("click", () => setOpen(false));
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && drawer.classList.contains("is-open"))
      setOpen(false);
  });
}

/** Route name (meta) + home hash / scroll position */
function initSiteNavActive() {
  const nav = document.querySelector("#site-header");
  if (!nav) return;

  const links = [...nav.querySelectorAll("a.site-nav-link[data-nav-active]")];
  const drawerLinks = document.querySelectorAll(
    "[data-nav-drawer] a.site-nav-link[data-nav-active]",
  );
  const all = [...links, ...drawerLinks];
  const unique = [...new Set(all)];

  const routeName = metaContent("site-route-name");
  const isHome = routeName === "home";

  const clearActive = () => {
    unique.forEach((a) => a.classList.remove("active"));
  };

  const setProductsActive = () => {
    clearActive();
    unique
      .filter((a) => a.getAttribute("data-nav-active") === "products")
      .forEach((a) => a.classList.add("active"));
  };

  const setSectionActive = (id) => {
    clearActive();
    if (!id) return;
    unique
      .filter((a) => a.getAttribute("data-nav-active") === `section:${id}`)
      .forEach((a) => a.classList.add("active"));
  };

  const applyFromRoute = () => {
    if (routeName.startsWith("products.")) {
      setProductsActive();
      return true;
    }
    return false;
  };

  const sectionIds = ["services", "about", "contact"];

  const pickSectionFromScroll = () => {
    if (!isHome) return null;
    const headerEl = document.querySelector("#site-header");
    const headerH = headerEl?.offsetHeight ?? 120;
    const mid = window.scrollY + headerH + 40;
    let best = null;
    let bestTop = -Infinity;
    for (const id of sectionIds) {
      const el = document.getElementById(id);
      if (!el) continue;
      const r = el.getBoundingClientRect();
      const top = r.top + window.scrollY;
      if (top <= mid && top >= bestTop) {
        bestTop = top;
        best = id;
      }
    }
    return best;
  };

  const applyHomeSections = () => {
    if (!isHome) return;
    const hash = (window.location.hash || "").replace(/^#/, "");
    if (hash && sectionIds.includes(hash)) {
      setSectionActive(hash);
      return;
    }
    const picked = pickSectionFromScroll();
    if (picked) {
      setSectionActive(picked);
    } else {
      clearActive();
    }
  };

  if (applyFromRoute()) {
    /* products page */
  } else if (isHome) {
    applyHomeSections();
  } else {
    clearActive();
  }

  window.addEventListener("hashchange", () => {
    if (applyFromRoute()) return;
    if (isHome) applyHomeSections();
  });

  window.addEventListener(
    "scroll",
    () => {
      if (routeName.startsWith("products.")) return;
      if (isHome && !(window.location.hash || "").replace(/^#/, "")) {
        applyHomeSections();
      }
    },
    { passive: true },
  );
}

function initSiteSearch() {
  const openBtn = document.querySelector("[data-site-search-open]");
  const overlay = document.getElementById("site-search-overlay");
  if (!openBtn || !overlay) return;

  const form = overlay.querySelector("form");
  const input = overlay.querySelector('input[name="q"]');
  const productsUrl = metaContent("products-index-url");
  if (form && productsUrl) {
    form.action = productsUrl;
  }

  const setOpen = (open) => {
    if (open) closeOtherOverlays("search");
    overlay.classList.toggle("is-open", open);
    overlay.setAttribute("aria-hidden", String(!open));
    openBtn.setAttribute("aria-expanded", String(open));
    document.body.classList.toggle("site-search-open", open);
    if (open) {
      window.setTimeout(() => input?.focus(), 200);
    }
  };

  openBtn.addEventListener("click", () =>
    setOpen(!overlay.classList.contains("is-open")),
  );

  overlay.querySelectorAll("[data-site-search-close]").forEach((btn) => {
    btn.addEventListener("click", () => setOpen(false));
  });

  overlay.addEventListener("click", (e) => {
    if (
      e.target === overlay ||
      e.target?.closest?.("[data-site-search-dismiss]")
    ) {
      setOpen(false);
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && overlay.classList.contains("is-open")) {
      setOpen(false);
    }
  });
}

function storefrontCurrency() {
  return metaContent("site-default-currency") || "EGP";
}

function formatMoney(amount) {
  const n = Number(amount);
  if (Number.isNaN(n)) return "—";
  return `${storefrontCurrency()} ${n.toFixed(2)}`;
}

function cartAddedToastMessage() {
  const loc = metaContent("app-locale") || document.documentElement.lang || "en";
  return loc.toLowerCase().startsWith("ar")
    ? "تمت إضافة المنتج إلى العربة"
    : "Added to your bag";
}

function showCartToast(message, isError = false) {
  const text = message || cartAddedToastMessage();
  let stack = document.getElementById("cart-toast-stack");
  if (!stack) {
    stack = document.createElement("div");
    stack.id = "cart-toast-stack";
    stack.className = "cart-toast-stack";
    stack.setAttribute("aria-live", "polite");
    document.body.appendChild(stack);
  }
  const el = document.createElement("div");
  el.className = `cart-toast${isError ? " cart-toast--error" : ""}`;
  el.setAttribute("role", "status");
  el.textContent = text;
  stack.appendChild(el);
  requestAnimationFrame(() => el.classList.add("cart-toast--show"));
  const t = window.setTimeout(() => {
    el.classList.remove("cart-toast--show");
    window.setTimeout(() => el.remove(), 280);
  }, 3600);
  el.addEventListener("click", () => {
    window.clearTimeout(t);
    el.remove();
  });
}

function resolveImageUrl(path) {
  if (!path) return "";
  const s = String(path);
  if (s.startsWith("http://") || s.startsWith("https://")) return s;
  if (s.startsWith("//")) return `${window.location.protocol}${s}`;
  if (s.startsWith("/")) return `${window.location.origin}${s}`;
  return `${window.location.origin}/${s.replace(/^\//, "")}`;
}

function productShowUrl(slug) {
  const tpl = metaContent("product-show-url-template");
  if (!tpl || !slug) return "";
  return tpl.split("__SLUG__").join(encodeURIComponent(String(slug)));
}

function initMiniCart() {
  const openBtn = document.querySelector("[data-mini-cart-open]");
  const panelRoot = document.getElementById("mini-cart");
  if (!openBtn || !panelRoot) return;

  const escapeHtml = (str) =>
    String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");

  const bodyEl = panelRoot.querySelector("[data-mini-cart-body]");
  const footEl = panelRoot.querySelector("[data-mini-cart-foot]");
  const subtotalEl = panelRoot.querySelector("[data-mini-cart-subtotal]");
  const checkoutLink = panelRoot.querySelector("[data-mini-cart-checkout]");
  const removeTpl = metaContent("cart-remove-url-template");
  const checkoutUrl = metaContent("checkout-url");

  if (checkoutLink && checkoutUrl) {
    checkoutLink.setAttribute("href", checkoutUrl);
  }

  const setOpen = (open) => {
    if (open) closeOtherOverlays("cart");
    panelRoot.classList.toggle("is-open", open);
    panelRoot.setAttribute("aria-hidden", String(!open));
    openBtn.setAttribute("aria-expanded", String(open));
    document.body.classList.toggle("mini-cart-open", open);
    if (open) {
      refreshMiniCart();
    }
  };

  async function fetchCart() {
    const url = metaContent("cart-get-url");
    if (!url) return null;
    const res = await fetch(url, {
      credentials: "same-origin",
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });
    if (!res.ok) return null;
    return res.json();
  }

  async function removeLine(id) {
    if (!removeTpl) return;
    const url = removeTpl.split("__ITEM__").join(String(id));
    const res = await fetch(url, {
      method: "DELETE",
      credentials: "same-origin",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken(),
        "X-Requested-With": "XMLHttpRequest",
      },
    });
    if (!res.ok) return;
    const data = await res.json().catch(() => null);
    if (data) syncCartBadges(data);
    await refreshMiniCart();
  }

  async function refreshMiniCart() {
    if (!bodyEl) return;
    const data = await fetchCart();
    if (!data) {
      bodyEl.innerHTML =
        '<p class="font-body text-sm text-on-surface-variant py-8 text-center">Could not load bag.</p>';
      footEl?.classList.add("hidden");
      return;
    }

    syncCartBadges(data, true);

    const items = data.items || [];
    if (items.length === 0) {
      bodyEl.innerHTML =
        '<p class="font-body text-sm text-on-surface-variant py-10 text-center">Your bag is empty.</p>';
      footEl?.classList.add("hidden");
      return;
    }

    footEl?.classList.remove("hidden");
    if (subtotalEl) {
      subtotalEl.textContent = formatMoney(data.subtotal);
    }

    bodyEl.innerHTML = items
      .map((item) => {
        const img = resolveImageUrl(item.product_image);
        const href = productShowUrl(item.product_slug);
        const nameHtml = href
          ? `<a href="${href}" class="font-body text-sm text-on-surface hover:text-primary transition-colors">${escapeHtml(item.product_name || "")}</a>`
          : `<span class="font-body text-sm text-on-surface">${escapeHtml(item.product_name || "")}</span>`;
        const v = item.variation_name
          ? `<p class="text-[10px] uppercase tracking-wider text-on-surface-variant mt-0.5">${escapeHtml(item.variation_name)}</p>`
          : "";
        const srcAttr = img.replace(/"/g, "&quot;");
        return `<div class="mini-cart__line" data-cart-line="${item.id}">
          ${img ? `<img class="mini-cart__line-img" src="${srcAttr}" alt=""/>` : '<div class="mini-cart__line-img"></div>'}
          <div class="min-w-0 flex-1">
            ${nameHtml}
            ${v}
            <p class="font-label text-[10px] uppercase tracking-wider text-on-surface-variant mt-1">${escapeHtml(String(item.quantity))} × ${formatMoney(item.unit_price)}</p>
            <button type="button" class="mini-cart__line-remove" data-remove-cart-item="${item.id}">Remove</button>
          </div>
        </div>`;
      })
      .join("");

    bodyEl.querySelectorAll("[data-remove-cart-item]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-remove-cart-item");
        if (id) removeLine(id);
      });
    });
  }

  openBtn.addEventListener("click", () =>
    setOpen(!panelRoot.classList.contains("is-open")),
  );

  panelRoot.querySelector("[data-mini-cart-close]")?.addEventListener("click", () => setOpen(false));

  panelRoot.querySelector("[data-mini-cart-dismiss]")?.addEventListener("click", () => setOpen(false));

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && panelRoot.classList.contains("is-open")) {
      setOpen(false);
    }
  });

  document.addEventListener("nidan:cart-updated", () => {
    if (panelRoot.classList.contains("is-open")) {
      refreshMiniCart();
    }
  });
}

function initSmoothHomeAnchors() {
  document.querySelectorAll("a.js-smooth-home").forEach((a) => {
    a.addEventListener("click", (e) => {
      const id = a.getAttribute("data-home-anchor");
      if (!id || !isHomePath()) return;
      const href = a.getAttribute("href") || "";
      if (!href.startsWith("#")) return;
      const el = document.getElementById(id);
      if (!el) return;
      e.preventDefault();
      el.scrollIntoView({
        behavior: prefersReducedMotion() ? "auto" : "smooth",
        block: "start",
      });
      history.pushState(null, "", `#${id}`);
      document.dispatchEvent(
        new CustomEvent("nidan:home-section-hash", { detail: { id } }),
      );
    });
  });
}

function initHomeHashScroll() {
  if (metaContent("site-route-name") !== "home") return;
  const raw = window.location.hash.replace(/^#/, "");
  if (!raw) return;
  const el = document.getElementById(raw);
  if (!el) return;
  const run = () =>
    el.scrollIntoView({
      behavior: prefersReducedMotion() ? "auto" : "smooth",
      block: "start",
    });
  window.requestAnimationFrame(() => window.setTimeout(run, 100));
}

function initUploadPreview() {
  const input = document.querySelector("#bloom-upload");
  const trigger = document.querySelector("#bloom-upload-trigger");
  const preview = document.querySelector("#bloom-preview");
  const previewImg = preview?.querySelector("img");
  const errEl = document.querySelector("#bloom-upload-error");
  const okEl = document.querySelector("#bloom-upload-success");
  const uploadUrl = metaContent("bespoke-upload-url");

  if (!input || !preview || !previewImg) return;

  const MAX_BYTES = 8 * 1024 * 1024;
  const ALLOWED = new Set([
    "image/jpeg",
    "image/png",
    "image/webp",
    "image/gif",
  ]);

  const showError = (msg) => {
    if (errEl) {
      errEl.textContent = msg || "";
      errEl.classList.toggle("hidden", !msg);
    }
    if (okEl) {
      okEl.textContent = "";
      okEl.classList.add("hidden");
    }
    preview.classList.add("hidden");
    preview.classList.remove("is-visible", "is-error");
    previewImg.removeAttribute("src");
  };

  trigger?.addEventListener("click", () => input.click());

  input.addEventListener("change", async () => {
    showError("");
    const file = input.files?.[0];
    if (!file) {
      preview.classList.add("hidden");
      return;
    }

    if (!ALLOWED.has(file.type)) {
      showError("Please choose a JPEG, PNG, WebP, or GIF image.");
      preview.classList.add("is-error");
      input.value = "";
      return;
    }
    if (file.size > MAX_BYTES) {
      showError("Image must be 8 MB or smaller.");
      preview.classList.add("is-error");
      input.value = "";
      return;
    }

    const url = URL.createObjectURL(file);
    previewImg.onload = () => URL.revokeObjectURL(url);
    previewImg.src = url;
    previewImg.alt = file.name;
    preview.classList.remove("hidden");
    preview.classList.add("is-visible");
    preview.classList.remove("is-error");

    if (!uploadUrl) return;

    const fd = new FormData();
    fd.append("photo", file);
    try {
      const res = await fetch(uploadUrl, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": csrfToken(),
          "X-Requested-With": "XMLHttpRequest",
        },
        body: fd,
      });
      const data = res.ok ? await res.json().catch(() => ({})) : null;
      if (res.ok && data?.ok) {
        if (okEl) {
          okEl.textContent = "Thank you — we received your photo.";
          okEl.classList.remove("hidden");
        }
        return;
      }
      let msg = "Upload could not be completed. Please try again.";
      if (!res.ok) {
        try {
          const err = await res.json();
          if (err?.message) msg = err.message;
          else if (err?.errors?.photo?.[0]) msg = err.errors.photo[0];
        } catch {
          /* ignore */
        }
      }
      showError(msg);
      input.value = "";
    } catch {
      showError("Upload could not be completed. Please try again.");
      input.value = "";
    }
  });
}

function initHomeCartQuickAdd() {
  const url = metaContent("cart-add-url");
  if (!url) return;

  document.querySelectorAll("[data-cart-add]").forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();
      e.stopPropagation();
      const id = btn.getAttribute("data-cart-add");
      if (!id) return;
      try {
        const res = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken(),
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify({ product_id: parseInt(id, 10), quantity: 1 }),
        });
        if (!res.ok) {
          const errLoc = metaContent("app-locale") || "";
          showCartToast(
            errLoc.toLowerCase().startsWith("ar")
              ? "تعذّرت الإضافة — حاول مرة أخرى"
              : "Could not add — please try again",
            true,
          );
          return;
        }
        const data = await res.json();
        syncCartBadges(data);
        showCartToast();
      } catch {
        const errLoc = metaContent("app-locale") || "";
        showCartToast(
          errLoc.toLowerCase().startsWith("ar")
            ? "تعذّرت الإضافة — حاول مرة أخرى"
            : "Could not add — please try again",
          true,
        );
      }
    });
  });
}

/** Intersection Observer adds .fade-in only */
function initScrollReveal() {
  if (prefersReducedMotion()) {
    document.querySelectorAll(".js-reveal").forEach((el) => {
      el.classList.add("fade-in");
    });
    return;
  }

  const nodes = document.querySelectorAll(".js-reveal");
  if (!nodes.length) return;

  const io = new IntersectionObserver(
    (entries) => {
      for (const en of entries) {
        if (!en.isIntersecting) continue;
        en.target.classList.add("fade-in");
        io.unobserve(en.target);
      }
    },
    { root: null, rootMargin: "0px 0px -8% 0px", threshold: 0.12 },
  );

  nodes.forEach((el) => io.observe(el));
}

/** Partners: pause/resume via .is-paused (CSS animation-play-state) */
function initMarquee() {
  const mq = document.querySelector("[data-marquee]");
  if (!mq || prefersReducedMotion()) return;

  mq.addEventListener("mouseenter", () => mq.classList.add("is-paused"));
  mq.addEventListener("mouseleave", () => mq.classList.remove("is-paused"));
  mq.addEventListener("focusin", () => mq.classList.add("is-paused"));
  mq.addEventListener("focusout", () => mq.classList.remove("is-paused"));
}

function initHoverEnhancements() {
  const bind = (sel, active = "is-active") => {
    document.querySelectorAll(sel).forEach((el) => {
      el.addEventListener("mouseenter", () => el.classList.add(active));
      el.addEventListener("mouseleave", () => el.classList.remove(active));
      el.addEventListener("focusin", () => el.classList.add(active));
      el.addEventListener("focusout", () => el.classList.remove(active));
    });
  };

  bind(".js-hover-card");
  bind(".js-hover-btn");
  bind(".js-social-icon");
}

/**
 * Product cards: scale the masked blob container (not the <img>) so the organic border-radius is preserved.
 */
function initProductCardImageHover() {
  document.querySelectorAll(".product-gallery-card").forEach((card) => {
    const container = card.querySelector(".product-card-image-container");
    if (!container) return;

    const bg = card.querySelector(".product-blob-bg");

    const activate = () => {
      container.classList.add("scale-active");
      bg?.classList.add("scale-active");
    };

    const deactivate = () => {
      container.classList.remove("scale-active");
      bg?.classList.remove("scale-active");
    };

    card.addEventListener("mouseenter", activate);
    card.addEventListener("mouseleave", deactivate);
    card.addEventListener("focusin", (e) => {
      if (card.contains(e.target)) activate();
    });
    card.addEventListener("focusout", (e) => {
      const next = e.relatedTarget;
      if (!next || !card.contains(next)) deactivate();
    });
  });
}

function boot() {
  initStickyHeader();
  initMobileNav();
  initSiteNavActive();
  initSiteSearch();
  initMiniCart();
  initSmoothHomeAnchors();
  initHomeHashScroll();
  initUploadPreview();
  initHomeCartQuickAdd();
  initScrollReveal();
  initMarquee();
  initHoverEnhancements();
  initProductCardImageHover();
  initVisualEditor();
}

window.addEventListener("DOMContentLoaded", () => {
  boot();
});
