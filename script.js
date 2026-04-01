

// ═══════════════════════════════════════
//   LA PIAZZA — script.js
// ═══════════════════════════════════════

// ── 1. NAVBAR SCROLL EFFECT ──
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// ── 2. HAMBURGER MENU (MOBILE) ──
const hamburger = document.getElementById('hamburger');
const navMenu = document.querySelector('nav ul');

hamburger.addEventListener('click', () => {
  navMenu.classList.toggle('open');
});

// fermer le menu quand on clique un lien
document.querySelectorAll('nav ul a').forEach(link => {
  link.addEventListener('click', () => {
    navMenu.classList.remove('open');
  });
});

// ── 3. COUNTER ANIMATION (STATS STRIP) ──
function animateCounter(el, target, duration = 1500) {
  let start = 0;
  const step = target / (duration / 16);

  const timer = setInterval(() => {
    start += step;
    if (start >= target) {
      el.textContent = target + (target === 100 ? '' : '+');
      clearInterval(timer);
    } else {
      el.textContent = Math.floor(start);
    }
  }, 16);
}

// déclencher quand la section est visible
const counters = document.querySelectorAll('.num[data-target]');

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const target = parseInt(entry.target.dataset.target);
      animateCounter(entry.target, target);
      counterObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

counters.forEach(c => counterObserver.observe(c));

// ── 4. SCROLL REVEAL ANIMATION ──
const revealElements = document.querySelectorAll(
  '.story-text, .story-img, .menu-card, .spec-item'
);

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

revealElements.forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
  revealObserver.observe(el);
});

// ── 5. RESERVATION FORM VALIDATION ──
const resBtn = document.getElementById('res-btn');
const resMessage = document.getElementById('res-message');

if (resBtn) {
  resBtn.addEventListener('click', () => {
    const name    = document.getElementById('res-name').value.trim();
    const date    = document.getElementById('res-date').value;
    const persons = document.getElementById('res-persons').value;

    // Validation
    if (!name || !date || !persons) {
      resMessage.textContent = '⚠ Veuillez remplir tous les champs.';
      resMessage.className = 'res-message error';
      return;
    }

    // Vérifier que la date est dans le futur
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
      resMessage.textContent = '⚠ Veuillez choisir une date future.';
      resMessage.className = 'res-message error';
      return;
    }

    // Succès (sans backend pour l'instant)
    resMessage.textContent = `✓ Merci ${name} ! Votre table pour ${persons} le ${formatDate(date)} est confirmée.`;
    resMessage.className = 'res-message success';

    // Reset form
    document.getElementById('res-name').value = '';
    document.getElementById('res-date').value = '';
    document.getElementById('res-persons').value = '';

    // Effacer le message après 5s
    setTimeout(() => {
      resMessage.textContent = '';
      resMessage.className = 'res-message';
    }, 5000);
  });
}

// Helper: formater la date en français
function formatDate(dateStr) {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateStr).toLocaleDateString('fr-FR', options);
}

// ── 6. SMOOTH ACTIVE LINK IN NAVBAR ──
const sections = document.querySelectorAll('section[id], div[id]');
const navLinks = document.querySelectorAll('nav ul a');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 120;
    if (window.scrollY >= sectionTop) {
      current = section.getAttribute('id');
    }
  });

  navLinks.forEach(link => {
    link.style.color = '';
    if (link.getAttribute('href') === `#${current}`) {
      link.style.color = 'var(--gold)';
    }
  });
}); 
