(function(){
  const WRAP = document.querySelector('#recensioni .cards.three');
  const BTN  = document.getElementById('load-more-reviews');
  if(!WRAP) return;

  const PAGE_SIZE = 12;
  let offset = 0;
  let loading = false;
  let total = 0;

  function stars(n){
    n = Math.max(1, Math.min(5, Number(n)||0));
    return '★★★★★'.slice(0,n) + '☆☆☆☆☆'.slice(0,5-n);
  }
  function initials(name){
    if(!name) return '';
    return name.trim().split(/\s+/).map(s=>s[0]?.toUpperCase()).join('').slice(0,2);
  }
  function makeCard(r){
    const art = document.createElement('article');
    art.className = 'card t-card'; art.setAttribute('role','listitem'); art.setAttribute('data-auto','1');
    const p = document.createElement('p'); p.className = 't-quote'; p.textContent = '“' + (r.text||'') + '”'; art.appendChild(p);
    const meta = document.createElement('div'); meta.className = 't-meta';
    const av = document.createElement('span'); av.className = 't-avatar'; av.setAttribute('aria-hidden','true'); av.textContent = initials(r.name||''); meta.appendChild(av);
    const person = document.createElement('div'); person.className = 't-person';
    const nm = document.createElement('span'); nm.className = 't-name'; nm.textContent = r.name || 'Cliente'; person.appendChild(nm);
    const rl = document.createElement('span'); rl.className = 't-role'; rl.textContent = r.role || 'Cliente'; person.appendChild(rl);
    meta.appendChild(person);
    const st = document.createElement('span'); st.className = 't-stars'; st.setAttribute('aria-label','Valutazione '+(r.rating||5)+' su 5'); st.textContent = stars(r.rating||5); meta.appendChild(st);
    art.appendChild(meta);
    return art;
  }

  async function fetchPage(){
    if(loading) return;
    loading = true;
    try{
      const res = await fetch('list-reviews.php?limit='+PAGE_SIZE+'&offset='+offset, { headers: { 'Accept':'application/json' } });
      if(!res.ok) return;
      const data = await res.json();
      if(!data.ok || !Array.isArray(data.reviews)) return;
      total = data.total || total;
      data.reviews.forEach(r => WRAP.appendChild(makeCard(r)));
      offset += data.reviews.length;
      updateButton();
    }catch(e){ console.error(e); }
    finally{ loading = false; }
  }

  function updateButton(){
    if(!BTN) return;
    if(offset >= total || total === 0){
      BTN.style.display = 'none';
    } else {
      BTN.style.display = '';
      BTN.textContent = 'Mostra altre (' + Math.min(PAGE_SIZE, total - offset) + ')';
    }
  }

  fetchPage();
  if(BTN){ BTN.addEventListener('click', (e)=>{ e.preventDefault(); fetchPage(); }); }
})();