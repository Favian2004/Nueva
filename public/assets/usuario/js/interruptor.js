// ============================================================
    //  INTERRUPTOR TRABAJADOR / EMPLEADOR (AJUSTADO, A PRUEBA DE
    //  PÁGINAS QUE NO TENGAN TODOS LOS ELEMENTOS, ej. profile.blade.php)
    // ============================================================
    (function() {
      const labelWorker = document.getElementById('labelWorker');
      const labelEmployer = document.getElementById('labelEmployer');
      const activeBg = document.getElementById('activeBg');
      const roleNameDisplay = document.getElementById('roleNameDisplay');
      const roleNotice = document.getElementById('roleNotice');
      const tooltip = document.getElementById('roleTooltip');
      const tooltipRoleName = document.getElementById('tooltipRoleName');
      const tooltipDesc = document.getElementById('tooltipDesc');

      // Si la página no tiene el interruptor completo (ej. profile.blade.php,
      // que no trae el bloque .sidebar-role-switch), no hacemos nada más.
      if (!labelWorker || !labelEmployer || !activeBg) {
        return;
      }

      // Elementos que se ocultan/muestran según rol
      const workerOnlyElements = [
        document.getElementById('sidebarVerEmpleos'),
        document.getElementById('sidebarPublicar'),
        document.getElementById('sidebarMisEmpleos'),
        document.getElementById('sidebarSolicitudes'),
        document.getElementById('sidebarProfile'),
        document.getElementById('navProfile'),
        document.getElementById('actionPublicar'),
        document.getElementById('actionVer'),
        document.getElementById('actionMis')
      ];

      const employerOnlyElements = [
        document.getElementById('sidebarBuscarTalento'),
        document.getElementById('sidebarMisVacantes'),
        document.getElementById('sidebarPostulantes'),
        document.getElementById('sidebarPublicarVacante')
      ];

      let isWorker = true;
      let tooltipTimeout;

      function showTooltip() {
        if (!tooltip) return;
        clearTimeout(tooltipTimeout);
        tooltip.classList.add('show');
        tooltipTimeout = setTimeout(() => {
          tooltip.classList.remove('show');
        }, 2500);
      }

      function updateUI() {
        labelWorker.classList.toggle('active', isWorker);
        labelEmployer.classList.toggle('active', !isWorker);
        activeBg.className = 'active-bg ' + (isWorker ? 'worker-bg' : 'employer-bg');

        const roleText = isWorker ? 'Trabajador' : 'Empleador';
        if (roleNameDisplay) {
          roleNameDisplay.textContent = roleText;
          roleNameDisplay.className = 'notice-role ' + (isWorker ? 'worker' : 'employer');
        }

        if (roleNotice) {
          const noticeIcon = roleNotice.querySelector('.notice-icon');
          if (noticeIcon) noticeIcon.textContent = isWorker ? '👷' : '🏢';

          const noticeText = roleNotice.querySelector('span:last-child');
          if (noticeText) {
            if (isWorker) {
              noticeText.innerHTML = 'Estás en modo <strong class="notice-role worker">Trabajador</strong> · Puedes gestionar tus servicios y buscar empleos.';
            } else {
              noticeText.innerHTML = 'Estás en modo <strong class="notice-role employer">Empleador</strong> · <a href="/usuario/empleador" style="color:#2563eb; text-decoration:underline;">Ir al panel de empleador</a>';
            }
          }
        }

        if (tooltipRoleName) {
          tooltipRoleName.innerHTML = isWorker
            ? '👷 <span class="highlight-worker">Modo Trabajador</span>'
            : '🏢 <span class="highlight-employer">Modo Empleador</span>';
        }
        if (tooltipDesc) {
          tooltipDesc.textContent = isWorker ? 'Publica y gestiona tus servicios' : 'Panel exclusivo para contratar talento';
        }

        workerOnlyElements.forEach(el => {
          if (el) el.style.display = isWorker ? '' : 'none';
        });

        employerOnlyElements.forEach(el => {
          if (el) el.style.display = isWorker ? 'none' : '';
        });

        if (!isWorker) {
          const currentPage = window.location.pathname.replace(/^\/usuario\/?/, '') || 'index';
          const workerPages = ['index', 'verEmpleos', 'publicarEmpleo', 'misEmpleos', 'solicitudes'];
          if (workerPages.includes(currentPage)) {
            window.location.href = '/usuario/empleador';
          }
        }
      }

      function toggleRole(e) {
        e.stopPropagation();
        isWorker = !isWorker;
        updateUI();
        showTooltip();
        localStorage.setItem('userRole', isWorker ? 'worker' : 'employer');
      }

      labelWorker.addEventListener('click', function(e) {
        if (!isWorker) toggleRole(e);
      });
      labelEmployer.addEventListener('click', function(e) {
        if (isWorker) toggleRole(e);
      });

      labelWorker.setAttribute('tabindex', '0');
      labelEmployer.setAttribute('tabindex', '0');

      const switchRow = document.querySelector('.switch-row');
      if (switchRow) {
        switchRow.addEventListener('mouseenter', function() {
          if (tooltip) tooltip.classList.add('show');
        });
        switchRow.addEventListener('mouseleave', function() {
          clearTimeout(tooltipTimeout);
          setTimeout(() => { if (tooltip) tooltip.classList.remove('show'); }, 200);
        });
      }

      const savedRole = localStorage.getItem('userRole');
      if (savedRole === 'employer') isWorker = false;
      updateUI();

      const workerPages = ['index', 'verEmpleos', 'publicarEmpleo', 'misEmpleos', 'solicitudes'];
      const currentPage = window.location.pathname.replace(/^\/usuario\/?/, '') || 'index';

      if (savedRole === 'employer' && workerPages.includes(currentPage) && currentPage !== 'empleador') {
        window.location.href = '/usuario/empleador';
      }
      if ((savedRole === 'worker' || !savedRole) && currentPage === 'empleador') {
        window.location.href = '/usuario';
      }
    })();
