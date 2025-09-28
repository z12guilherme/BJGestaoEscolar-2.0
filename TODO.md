# TODO: Migrar Projeto para Laravel

## Passo 1: Configurar Laravel
- [ ] Instalar dependências no laravel_temp: cd laravel_temp && php ../composer.phar install
- [ ] Configurar .env com DB settings (MySQL, host 127.0.0.1:3307, db gestao_escolar, user root, pass '')
- [ ] Gerar key: php artisan key:generate

## Passo 2: Migrações do Banco
- [ ] Criar migration para usuarios: php artisan make:migration create_usuarios_table
- [ ] Criar migration para escolas: php artisan make:migration create_escolas_table
- [ ] Criar migration para professores: php artisan make:migration create_professores_table
- [ ] Criar migration para tutores: php artisan make:migration create_tutores_table
- [ ] Criar migration para diretores: php artisan make:migration create_diretores_table
- [ ] Criar migration para responsaveis: php artisan make:migration create_responsaveis_table
- [ ] Criar migration para turmas: php artisan make:migration create_turmas_table
- [ ] Criar migration para alunos: php artisan make:migration create_alunos_table
- [ ] Criar migration para laudos: php artisan make:migration create_laudos_table
- [ ] Criar migration para notas: php artisan make:migration create_notas_table
- [ ] Criar migration para professor_turma: php artisan make:migration create_professor_turma_table
- [ ] Adicionar colunas extras via migrations separadas (email, escola_id, etc.)
- [ ] Executar migrations: php artisan migrate

## Passo 3: Modelos Eloquent
- [ ] Criar modelos: User, Escola, Professor, Tutor, Diretor, Responsavel, Turma, Aluno, Laudo, Nota
- [ ] Definir relacionamentos em cada modelo (belongsTo, hasMany, etc.)
- [ ] Usar fillable, casts, etc.

## Passo 4: Autenticação
- [ ] Configurar Laravel Auth: php artisan make:auth (ou Breeze/Telescope)
- [ ] Modificar User model para roles
- [ ] Criar middleware para roles (RoleMiddleware)
- [ ] Atualizar login para usar Laravel Auth

## Passo 5: Controladores
- [ ] Criar SchoolController: index, store, destroy
- [ ] Criar ProfessorController: index, store, destroy
- [ ] Criar TurmaController: index, store, destroy
- [ ] Criar AlunoController: index, store, destroy
- [ ] Criar LaudoController: index, store, destroy
- [ ] Migrar lógica de salvar_*.php e remover_*.php para métodos store/destroy

## Passo 6: Views Blade
- [ ] Converter index.php para login.blade.php
- [ ] Converter dashboard.php para dashboard.blade.php com tabs
- [ ] Criar partials para forms e tables
- [ ] Usar @extends, @section, etc.

## Passo 7: Rotas
- [ ] Definir rotas em web.php: auth routes, resource routes para controllers
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
