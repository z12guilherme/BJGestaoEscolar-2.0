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
- [ ] Configurar Laravel Auth: instalar Breeze ou Jetstream
- [x] Modificar User model para roles (já tem role)
- [ ] Criar middleware para roles (RoleMiddleware)
- [ ] Atualizar login para usar Laravel Auth

## Passo 5: Controladores
- [x] Criar SchoolController: index, store, destroy (feito, mas adicionar edit, update, show views)
- [x] Criar ProfessorController: index, store, destroy (feito, mas adicionar views)
- [ ] Criar TurmaController: index, store, destroy
- [ ] Criar AlunoController: index, store, destroy
- [ ] Criar LaudoController: index, store, destroy
- [ ] Migrar lógica de salvar_*.php e remover_*.php para métodos store/destroy

## Passo 6: Views Blade
- [ ] Converter index.php para login.blade.php
- [ ] Converter dashboard.php para dashboard.blade.php com tabs
- [x] Criar partials para forms e tables (layouts/app.blade.php criado)
- [x] Usar @extends, @section, etc. (schools/index.blade.php criado)

## Passo 7: Rotas
- [x] Definir rotas em web.php: auth routes, resource routes para controllers (schools e professores)
- [ ] Proteger rotas com middleware auth e role

## Passo 8: Assets
- [ ] Mover CSS/JS de static/ para resources/ e public/
- [ ] Compilar assets se necessário

## Passo 9: AJAX e Outros
- [ ] Migrar ajax/ endpoints para API routes ou controller methods
- [ ] Atualizar JavaScript para usar Laravel routes

## Passo 10: Testes e Deploy
- [ ] Testar login, CRUD operations
- [ ] Seed database com usuário root
- [ ] Atualizar README para Laravel
- [ ] Remover arquivos PHP antigos ou manter como backup

## Próximos Passos Imediatos
- [ ] Configurar autenticação mínima: instalar Breeze ou Jetstream
- [ ] Proteger rotas críticas: aplicar middleware auth e role nas rotas de schools e professores
- [ ] Completar CRUD básico para Schools e Professores: views para edit, update, show
- [ ] Converter views essenciais: index.php para login.blade.php, dashboard.php para dashboard.blade.php
- [ ] Testar funcionalidade mínima: rodar servidor Laravel e verificar login, dashboard, CRUD
