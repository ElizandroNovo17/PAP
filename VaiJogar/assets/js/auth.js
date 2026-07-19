// ============================================================
//  AUTH.JS — Sistema de Autenticação + Admin (VaiJogar PAP)
//  Guarda tudo em localStorage. Inclui conta admin padrão.
// ============================================================

const Auth = (() => {
  const USERS_KEY   = 'vaij_users';
  const SESSION_KEY = 'vaij_session';

  // ----- Hash simples (suficiente para PAP) -----
  function hashPassword(pass) {
    let h = 5381;
    for (let i = 0; i < pass.length; i++) {
      h = ((h << 5) + h) ^ pass.charCodeAt(i);
      h = h >>> 0;
    }
    return 'h_' + h.toString(36) + '_' + pass.length;
  }

  // ----- Dados persistentes -----
  function getUsers() {
    return JSON.parse(localStorage.getItem(USERS_KEY) || '[]');
  }
  function saveUsers(u) {
    localStorage.setItem(USERS_KEY, JSON.stringify(u));
  }

  // ----- Inicializa admin padrão -----
  function init() {
    const users = getUsers();
    if (!users.find(u => u.role === 'admin')) {
      users.push({
        id: 'admin_001',
        nome: 'Administrador',
        email: 'admin@vaijogar.pt',
        password: hashPassword('Admin@1234'),
        role: 'admin',
        avatar: null,
        bio: 'Conta de administração do sistema VaiJogar.',
        createdAt: new Date().toISOString()
      });
      saveUsers(users);
    }
  }

  // ----- Registo -----
  function register(nome, email, password) {
    const users = getUsers();
    if (users.find(u => u.email.toLowerCase() === email.toLowerCase())) {
      return { success: false, message: 'Este email já está registado.' };
    }
    const user = {
      id: 'user_' + Date.now(),
      nome,
      email,
      password: hashPassword(password),
      role: 'user',
      avatar: null,
      bio: '',
      createdAt: new Date().toISOString()
    };
    users.push(user);
    saveUsers(users);
    return { success: true, user: sanitize(user) };
  }

  // ----- Login -----
  function login(email, password) {
    const users = getUsers();
    const user = users.find(u =>
      u.email.toLowerCase() === email.toLowerCase() &&
      u.password === hashPassword(password)
    );
    if (!user) return { success: false, message: 'Email ou palavra-passe incorretos.' };
    localStorage.setItem(SESSION_KEY, JSON.stringify({ userId: user.id, at: Date.now() }));
    return { success: true, user: sanitize(user) };
  }

  // ----- Logout -----
  function logout() {
    localStorage.removeItem(SESSION_KEY);
    window.location.href = 'index.php';
  }

  // ----- Sessão atual -----
  function getCurrentUser() {
    const s = JSON.parse(localStorage.getItem(SESSION_KEY) || 'null');
    if (!s) return null;
    const u = getUsers().find(u => u.id === s.userId);
    return u ? sanitize(u) : null;
  }

  function isLoggedIn() { return getCurrentUser() !== null; }
  function isAdmin()    { const u = getCurrentUser(); return u && u.role === 'admin'; }

  // ----- Proteção de páginas -----
  function requireAuth()  { if (!isLoggedIn()) window.location.href = 'index.php'; }
  function requireAdmin() { if (!isAdmin())    window.location.href = 'escolha.php'; }

  // ----- Atualizar perfil -----
  function updateProfile({ nome, bio, avatar }) {
    const cur = getCurrentUser();
    if (!cur) return { success: false };
    const users = getUsers();
    const i = users.findIndex(u => u.id === cur.id);
    if (i === -1) return { success: false };
    if (nome  !== undefined) users[i].nome   = nome;
    if (bio   !== undefined) users[i].bio    = bio;
    if (avatar !== undefined) users[i].avatar = avatar;
    saveUsers(users);
    return { success: true, user: sanitize(users[i]) };
  }

  // ----- Mudar password -----
  function changePassword(currentPass, newPass) {
    const cur = getCurrentUser();
    if (!cur) return { success: false, message: 'Não autenticado.' };
    const users = getUsers();
    const i = users.findIndex(u => u.id === cur.id);
    if (users[i].password !== hashPassword(currentPass))
      return { success: false, message: 'Palavra-passe atual incorreta.' };
    users[i].password = hashPassword(newPass);
    saveUsers(users);
    return { success: true };
  }

  // ----- Admin: listar todos -----
  function getAllUsers() {
    if (!isAdmin()) return [];
    return getUsers().map(sanitize);
  }

  // ----- Admin: apagar utilizador -----
  function deleteUser(userId) {
    if (!isAdmin()) return { success: false };
    const users = getUsers().filter(u => u.id !== userId);
    saveUsers(users);
    return { success: true };
  }

  // ----- Admin: mudar role -----
  function setUserRole(userId, role) {
    if (!isAdmin()) return { success: false };
    const users = getUsers();
    const i = users.findIndex(u => u.id === userId);
    if (i === -1) return { success: false };
    users[i].role = role;
    saveUsers(users);
    return { success: true };
  }

  function sanitize({ password, ...rest }) { return rest; }

  return {
    init, register, login, logout,
    getCurrentUser, isLoggedIn, isAdmin,
    requireAuth, requireAdmin,
    updateProfile, changePassword,
    getAllUsers, deleteUser, setUserRole
  };
})();

// Inicializa sempre
Auth.init();