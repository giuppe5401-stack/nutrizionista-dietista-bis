(function(){
  const badge = document.getElementById('agg-rating');
  if(!badge) return;
  fetch('list-reviews.php?limit=200', { headers: { 'Accept':'application/json' } })
    .then(r=>r.ok?r.json():null)
    .then(d=>{
      if(!d || !d.ok || !Array.isArray(d.reviews) || d.reviews.length===0) return;
      const ratings = d.reviews.map(x=>Number(x.rating)||0).filter(Boolean);
      if(ratings.length===0) return;
      const avg = ratings.reduce((a,b)=>a+b,0)/ratings.length;
      const rounded = Math.round(avg*10)/10;
      const stars = '★★★★★'.slice(0, Math.round(avg)) + '☆☆☆☆☆'.slice(0, 5 - Math.round(avg));
      badge.textContent = stars + ' ' + String(rounded.toFixed(1)).replace('.',',') + ' ('+ratings.length+' recensioni)';
    })
    .catch(()=>{});
})();