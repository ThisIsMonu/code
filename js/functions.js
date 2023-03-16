function _domReady(f) {
  document.readyState === "loading"
    ? document.addEventListener("DOMContentLoaded", f)
    : f();
}
