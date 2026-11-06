<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Masuk · Teduh Rasa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script>
  // Token Teduh Rasa → Tailwind. Warna memakai kanal RGB agar modifier opasitas (mis. ring-primary/20) berfungsi.
  tailwind.config = {
    theme: {
      screens: { sm: '480px', md: '820px', lg: '1120px', xl: '1440px' },
      extend: {
        colors: {
          canvas: 'rgb(var(--canvas) / <alpha-value>)',
          surface: 'rgb(var(--surface) / <alpha-value>)',
          ink: 'rgb(var(--ink) / <alpha-value>)',
          muted: 'rgb(var(--muted) / <alpha-value>)',
          line: 'rgb(var(--line) / <alpha-value>)',
          primary: 'rgb(var(--primary) / <alpha-value>)',
          'on-primary': 'rgb(var(--on-primary) / <alpha-value>)',
          error: 'rgb(var(--error) / <alpha-value>)',
          maroon: '#5A1B24',
          sand: '#E9DBC3',
          'sand-dim': '#CDB99C'
        },
        fontFamily: {
          serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
          sans: ['"DM Sans"', 'system-ui', 'sans-serif']
        },
        borderRadius: { md: '8px', lg: '10px' }
      }
    }
  };
</script>
<style>
  :root{
    --canvas:246 239 227; --surface:251 247 239; --ink:59 15 22; --muted:125 106 98;
    --line:218 203 179; --primary:90 27 36; --on-primary:246 239 227; --error:155 34 38;
    color-scheme:light;
    box-sizing:border-box;
    padding-top:env(safe-area-inset-top,0px);
    padding-bottom:env(safe-area-inset-bottom,0px);
  }
  @media (prefers-color-scheme:dark){
    :root:not([data-theme="light"]){
      --canvas:38 13 18; --surface:50 18 25; --ink:241 230 212; --muted:187 165 151;
      --line:74 42 47; --primary:233 219 195; --on-primary:59 15 22; --error:240 146 143;
      color-scheme:dark;
    }
  }
  :root[data-theme="dark"]{
    --canvas:38 13 18; --surface:50 18 25; --ink:241 230 212; --muted:187 165 151;
    --line:74 42 47; --primary:233 219 195; --on-primary:59 15 22; --error:240 146 143;
    color-scheme:dark;
  }
  html{height:100%;scroll-padding-top:env(safe-area-inset-top,0px)}
</style>
</head>
<body class="min-h-full bg-canvas font-sans text-base leading-normal text-ink antialiased">

<main class="grid min-h-screen md:grid-cols-2">

  <!-- Panel merek -->
  <section aria-label="Teduh Rasa" class="relative flex flex-col justify-between overflow-hidden bg-maroon px-7 py-7 text-sand md:px-14 md:py-12">
    <div class="relative font-serif text-[26px] font-semibold tracking-[.02em]">Teduh Rasa</div>

    <svg viewBox="0 0 300 380" aria-hidden="true"
         class="pointer-events-none absolute left-1/2 top-1/2 hidden w-[min(78%,420px)] -translate-x-1/2 -translate-y-[52%] opacity-90 md:block">
      <g class="fill-none stroke-sand stroke-1">
        <path d="M30 380V150a120 120 0 0 1 240 0v230"/>
        <path d="M62 380V152a88 88 0 0 1 176 0v228"/>
        <path d="M94 380V154a56 56 0 0 1 112 0v226"/>
        <path d="M126 380V156a24 24 0 0 1 48 0v224"/>
      </g>
    </svg>

    <div class="relative">
      <p class="mt-5 max-w-[15em] font-serif text-2xl font-medium leading-tight md:mt-0 md:text-[clamp(26px,2.6vw,36px)]">
        Duduk sebentar, nikmati rasanya pelan-pelan.
      </p>
      <p class="mt-3.5 hidden text-sm text-sand-dim md:block">Kelola reservasi, pesanan, dan menu restoran Anda.</p>
    </div>
  </section>

  <!-- Formulir -->
  <section class="flex items-start justify-center px-6 pb-12 pt-9 md:items-center md:px-7 md:py-12">
    <div class="w-full max-w-[380px]">
      <h1 class="mb-2.5 font-serif text-[38px] font-semibold leading-[1.05] md:text-[44px]">Selamat datang kembali</h1>
      <p class="mb-[34px] text-muted">Masuk untuk melanjutkan ke dasbor Teduh Rasa.</p>

      <form id="f" novalidate>
        <!-- Email -->
        <div id="g-email" data-error="false" class="group mb-5">
          <label for="email" class="mb-2 block text-sm font-medium">Email</label>
          <input id="email" type="email" autocomplete="username" placeholder="nama@teduhrasa.id" aria-describedby="e-email"
                 class="h-[50px] w-full rounded-lg border border-line bg-surface px-4 text-ink transition-[border-color,box-shadow] duration-200 placeholder:text-muted/70 hover:border-muted focus-visible:border-primary focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-primary/20 group-data-[error=true]:border-error motion-reduce:transition-none">
          <p id="e-email" role="alert" class="mt-1.5 hidden text-[13px] text-error group-data-[error=true]:block">Masukkan alamat email yang valid.</p>
        </div>

        <!-- Kata sandi -->
        <div id="g-pass" data-error="false" class="group mb-5">
          <label for="pass" class="mb-2 block text-sm font-medium">Kata sandi</label>
          <div class="relative">
            <input id="pass" type="password" autocomplete="current-password" placeholder="Minimal 8 karakter" aria-describedby="e-pass"
                   class="h-[50px] w-full rounded-lg border border-line bg-surface pl-4 pr-[84px] text-ink transition-[border-color,box-shadow] duration-200 placeholder:text-muted/70 hover:border-muted focus-visible:border-primary focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-primary/20 group-data-[error=true]:border-error motion-reduce:transition-none">
            <button type="button" id="tg" aria-controls="pass" aria-pressed="false"
                    class="absolute right-1.5 top-1.5 h-[38px] rounded-md px-3 text-sm text-muted hover:text-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Tampilkan</button>
          </div>
          <p id="e-pass" role="alert" class="mt-1.5 hidden text-[13px] text-error group-data-[error=true]:block">Kata sandi minimal 8 karakter.</p>
        </div>

        <!-- Opsi -->
        <div class="mb-[26px] mt-1 flex items-center justify-between gap-3 text-sm">
          <label class="flex cursor-pointer items-center gap-2.5 text-muted">
            <input id="rem" type="checkbox" class="peer sr-only">
            <span class="grid h-[18px] w-[18px] place-items-center rounded-[5px] border border-muted transition-colors peer-checked:border-primary peer-checked:bg-primary peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-primary peer-checked:[&>svg]:opacity-100 motion-reduce:transition-none">
              <svg viewBox="0 0 12 12" class="h-[11px] w-[11px] fill-none stroke-on-primary stroke-[2.4] opacity-0"><path d="M2 6.5l2.6 2.6L10 3.4"/></svg>
            </span>
            Ingat saya
          </label>
          <a href="#" id="forgot" class="text-ink underline decoration-line underline-offset-[3px] hover:decoration-current focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Lupa kata sandi?</a>
        </div>

        <button id="go" type="submit"
                class="h-[52px] w-full rounded-lg bg-primary text-base font-medium text-on-primary transition duration-150 hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[.99] disabled:cursor-progress disabled:opacity-70 motion-reduce:transition-none">Masuk</button>

        <p id="st" role="status" aria-live="polite" class="mt-[18px] min-h-[22px] text-center text-sm text-muted"></p>
      </form>

      <p class="mt-7 text-center text-sm text-muted">
        Belum punya akun?
        <a href="#" id="reg" class="text-ink underline decoration-line underline-offset-[3px] hover:decoration-current focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Daftar di sini</a>
      </p>
    </div>
  </section>
</main>

<script>
(function(){
  var f=document.getElementById('f'),em=document.getElementById('email'),pw=document.getElementById('pass'),
      tg=document.getElementById('tg'),st=document.getElementById('st'),go=document.getElementById('go'),
      rem=document.getElementById('rem');
  try{var saved=localStorage.getItem('tr-email');if(saved){em.value=saved;rem.checked=true;}}catch(e){}

  tg.addEventListener('click',function(){
    var show=pw.type==='password';
    pw.type=show?'text':'password';
    tg.textContent=show?'Sembunyikan':'Tampilkan';
    tg.setAttribute('aria-pressed',show);
  });

  function flag(id,bad){document.getElementById(id).dataset.error=bad?'true':'false';}
  em.addEventListener('input',function(){flag('g-email',false);});
  pw.addEventListener('input',function(){flag('g-pass',false);});

  f.addEventListener('submit',function(ev){
    ev.preventDefault();
    var okE=/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value.trim()),okP=pw.value.length>=8;
    flag('g-email',!okE);flag('g-pass',!okP);
    if(!okE){em.focus();return;}
    if(!okP){pw.focus();return;}
    try{
      if(rem.checked)localStorage.setItem('tr-email',em.value.trim());
      else localStorage.removeItem('tr-email');
    }catch(e){}
    go.disabled=true;go.textContent='Memproses…';st.textContent='';
    // Ganti blok ini dengan permintaan ke server autentikasi Anda.
    setTimeout(function(){
      go.disabled=false;go.textContent='Masuk';
      st.textContent='Berhasil masuk. Mengalihkan ke dasbor…';
    },1100);
  });
  ['forgot','reg'].forEach(function(id){
    document.getElementById(id).addEventListener('click',function(e){e.preventDefault();});
  });
})();
</script>
</body>
</html>
