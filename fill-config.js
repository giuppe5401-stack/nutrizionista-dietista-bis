// Sostituisce tutte le occorrenze {{CHIAVE}} nel documento con i valori di CONFIG
(function(){
  const C = window.CONFIG || {};
  function replaceInString(s){
    return s.replace(/{{(\w+)}}/g, (_, k) => (C[k] != null ? String(C[k]) : _));
  }
  function walk(node){
    if (node.nodeType === 3) { // text
      node.nodeValue = replaceInString(node.nodeValue);
      return;
    }
    if (node.nodeType !== 1) return; // elements only
    // attributes
    for (const attr of Array.from(node.attributes || [])){
      const v = replaceInString(attr.value);
      if (v !== attr.value) node.setAttribute(attr.name, v);
    }
    for (const child of Array.from(node.childNodes)){ walk(child); }
  }
  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', ()=>walk(document.body));
  } else {
    walk(document.body);
  }
})();