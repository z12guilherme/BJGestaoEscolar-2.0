# TODO: Migrar Projeto para Laravel

## Passo 1: Configurar Laravel
- [x] Instalar dependências no laravel_temp: cd laravel_temp && php ../composer.phar install
- [x] Configurar .env com DB settings (MySQL, host 127.0.0.1:3307, db gestao_escolar, user root, pass '')
- [x] Gerar key: php artisan key:generate

## Passo 2: Migrações do Banco
- [x] Criar migration para usuarios: php artisan make:migration create_usuarios_table
- [x] Criar migration para escolas: php artisan make:migration create_escolas_table
- [x] Criar migration para professores: php artisan make:migration create_professores_table
- [x] Criar migration para tutores: php artisan make:migration create_tutores_table
- [x] Criar migration para diretores: php artisan make:migration create_diretores_table
- [x] Criar migration para responsaveis: php artisan make:migration create_responsaveis_table
- [x] Criar migration para turmas: php artisan make:migration create_turmas_table
- [x] Criar migration para alunos: php artisan make:migration create_alunos_table
- [x] Criar migration para laudos: php artisan make:migration create_laudos_table
- [x] Criar migration para notas: php artisan make:migration create_notas_table
- [x] Criar migration para professor_turma: php artisan make:migration create_professor_turma_table
- [x] Adicionar colunas extras via migrations separadas (email, escola_id, etc.)
- [x] Executar migrations: php artisan migrate

## Passo 3: Modelos Eloquent
- [x] Criar modelos: User, Escola, Professor, Tutor, Diretor, Responsavel, Turma, Aluno, Laudo, Nota
- [x] Definir relacionamentos em cada modelo (belongsTo, hasMany, etc.)
- [x] Usar fillable, casts, etc.

## Passo 4: Autenticação
- [x] Configurar Laravel Auth: php artisan make:auth (ou Breeze/Telescope)
- [x] Modificar User model para roles
- [x] Criar middleware para roles (RoleMiddleware)
- [x] Atualizar login para usar Laravel Auth

## Passo 5: Controladores
- [x] Criar SchoolController: index, store, destroy
- [x] Criar ProfessorController: index, store, destroy
- [x] Criar TurmaController: index, store, destroy
- [x] Criar AlunoController: index, store, destroy
- [x] Criar LaudoController: index, store, destroy
- [x] Migrar lógica de salvar_*.php e remover_*.php para métodos store/destroy

## Passo 6: Views Blade
- [x] Converter index.php para login.blade.php
- [x] Converter dashboard.php para dashboard.blade.php com tabs
- [x] Criar partials para forms e tables
- [x] Usar @extends, @section, etc.

## Passo 7: Rotas
- [x] Definir rotas em web.php: auth routes, resource routes para controllers
- [x] Proteger rotas com middleware auth e role

## Passo 8: Assets
- [x] Mover CSS/JS de static/ para resources/ e public/
- [x] Compilar assets se necessário

## Passo 9: AJAX e Outros
- [x] Migrar ajax/ endpoints para API routes ou controller methods
- [x] Atualizar JavaScript para usar Laravel routes

## Passo 10: Testes e Deploy
- [x] Testar login, CRUD operations
- [x] Seed database com usuário root
- [x] Atualizar README para Laravel
- [x] Remover arquivos PHP antigos ou manter como backup

# TODO List for Sistema de Login Web
- [x] Create Root user in database via Python shell
- [x] Add @root_required decorator in app.py
- [x] Add reset_password route in app.py
- [x] Add edit_user route in app.py
- [x] Add delete_user route in app.py
- [x] Create templates/reset_password.html
- [x] Create templates/edit_user.html
- [x] Update templates/manage_users.html with Actions column
- [x] Test the functionality
- [x] Commit and push changes
- [x] Remove self-registration feature
- [x] Restrict user creation to Root only
- [x] Create README.md with setup and run instructions
- [x] Create requirements.txt with dependencies
- [x] Create models.py for User model
- [x] Create app.py for Flask application and routes
- [x] Create init_db.py for database initialization (optional)
- [x] Create templates/base.html for base template
- [x] Create templates/register.html for registration form
- [x] Create templates/login.html for login form
- [x] Create templates/dashboard.html for protected dashboard
- [x] Create static/css/style.css for custom styles
- [x] Create static/js/script.js for front-end validation and interactions
- [x] Test the application locally (install deps, run app, verify functionality)
- [x] Enhance login form attractiveness: Add minimalist line icons, subtle animations, neon blue accents
- [x] Implement teacher as full users: Update models (add user_id to Teacher, turma_id to Student, relationships); update app.py (register_teacher creates User+Teacher, dashboard conditional for teachers, new routes for add/remove students per turma with ownership check); update templates (dashboard shows teacher turmas, new turma_manage.html); test DB recreation, logins, restrictions.
- [x] Implement superuser and user management: Add role and school_id to User model; create root superuser in init_db.py; add role_required decorator; update login to set role/school_id in session; add manage_users route for SecretarioEducacao; update dashboard to show manage_users link; create manage_users.html template; restrict access based on roles (SecretarioEducacao full, Diretor school, Professor own turmas).
- [x] Update register_school to associate school to Diretor if they are Diretor.
- [x] Update register_turma to allow Professor to create turmas in their school.
- [x] Update register_student to restrict based on role. For Diretor, only their school, for Professor, their school.
- [x] Restrict register_teacher to SecretarioEducacao.

# Grade Registration and Reports Implementation
- [x] Add Nota model to models.py
- [x] Update app.py: Add import for Nota, new professor_or_super_required decorator, /get_alunos/<turma_id> JSON route, /dashboard/add_nota GET/POST route, /dashboard/relatorios GET route
- [x] Create templates/add_nota.html with form (turma select, dynamic aluno select via JS, semestre and valor inputs)
- [x] Create templates/relatorios.html with tables per turma showing aluno, semestre, nota
- [x] Update templates/dashboard.html: Add conditional cards for "Cadastrar Notas" and "Relatórios" if role in ['Professor', 'SecretarioEducacao']
- [x] Create static/js/script.js: Add JS function to fetch and populate aluno dropdown on turma select change
- [x] Run python init_db.py to recreate DB with Nota table
- [x] Run python app.py and perform thorough testing: Create test data (school, teacher, turma, student), login as root and professor, test add_nota access/restrictions, add/edit notes, view reports, unauthorized access flashes, dynamic JS works
- [x] Remove self-registration feature
