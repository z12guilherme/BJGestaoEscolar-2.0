<<<<<<< HEAD
# 📚 BJGestaoEscolar 2.0 (PHP)

Sistema de **gestão escolar completo**, reescrito em **PHP** com integração a banco de dados MySQL.  
Esta é a versão 2.0 do projeto, migrada do Flask (Python) para PHP puro, mantendo todas as funcionalidades essenciais e melhorando a performance para ambientes locais como XAMPP.  
O objetivo é fornecer uma solução simples e prática para gerenciamento de escolas, incluindo alunos, professores, turmas, notas, laudos e relatórios.
=======
<<<<<<< HEAD
# 📚 BJGestaoEscolar 2.0 (PHP)

Sistema de **gestão escolar completo**, reescrito em **PHP** com integração a banco de dados MySQL.  
Esta é a versão 2.0 do projeto, migrada do Flask (Python) para PHP puro, mantendo todas as funcionalidades essenciais e melhorando a performance para ambientes locais como XAMPP.  
O objetivo é fornecer uma solução simples e prática para gerenciamento de escolas, incluindo alunos, professores, turmas, notas, laudos e relatórios.

---

## 🚀 Funcionalidades

- 👩‍🏫 Cadastro e gerenciamento de **professores**, **diretores**, **tutores** e **responsáveis**
- 👨‍🎓 Cadastro e gerenciamento de **alunos** com associação a turmas
- 🏫 Criação e administração de **escolas** e **turmas** (com anos letivos e professores responsáveis)
- 📊 Lançamento de **notas** e **laudos** (relatórios acadêmicos/comportamentais)
- 📅 Controle de **frequência** e **médias** (implementação básica via notas)
- 🔒 Sistema de **autenticação** com roles (root, diretor, professor, tutor, aluno, responsável)
- 📝 Emissão de **relatórios** simples via dashboard
- ⚡ Interface responsiva com **Bootstrap 5** e animações CSS

---

## 🛠 Tecnologias Utilizadas

- **PHP 8+** (backend com PDO para banco de dados)
- **MySQL** (banco de dados relacional)
- **Bootstrap 5** (frontend e UI responsiva)
- **HTML5, CSS3 e JavaScript** (com animações e AJAX para interações dinâmicas)
- **XAMPP** (ambiente local de desenvolvimento e teste)
- **Session-based Authentication** (sem frameworks externos para simplicidade)

---

## 📦 Estrutura do Projeto

```
BJGestaoEscolar-main/
├── index.php              # Página de login
├── dashboard.php          # Dashboard principal com tabs para gerenciamento
├── db.php                 # Conexão e inicialização do banco (PDO)
├── config.php             # Configurações de DB e constantes
├── auth.php               # Funções de autenticação e roles
├── salvar_*.php           # Scripts de inserção (ex: salvar_aluno.php)
├── remover_*.php          # Scripts de remoção (ex: remover_aluno.php)
├── ajax/                  # Endpoints AJAX (ex: get_alunos_by_turma.php)
├── alunos/                # Módulo de alunos (cadastrar.php, listar.php, etc.)
├── professores/           # Módulo de professores
├── turmas/                # Módulo de turmas
├── notas/                 # Módulo de notas (lancar.php, relatorio.php)
├── static/                # Assets estáticos (CSS, JS, imagens)
├── assets/                # Assets adicionais (CSS, JS, imagens)
└── README.md              # Este arquivo
```

O banco de dados é inicializado automaticamente na primeira conexão, criando tabelas como `usuarios`, `escolas`, `professores`, `alunos`, `turmas`, `laudos`, `notas`, etc.

---

## 🚀 Instalação e Configuração

### Pré-requisitos
- **XAMPP** (com Apache e MySQL/MariaDB ativados)
- PHP 8+ e MySQL 5.7+ (incluídos no XAMPP)
- Editor de código (VS Code recomendado)

### Passos de Instalação
1. **Baixe e instale o XAMPP**: Acesse [xampp.apache.org](https://www.apachefriends.org/) e instale a versão para Windows.
2. **Inicie os serviços**: Abra o XAMPP Control Panel e inicie Apache e MySQL (porta 3307 para MySQL se conflitar com outra instalação).
3. **Coloque os arquivos no htdocs**: Copie a pasta `BJGestaoEscolar-main` para `c:/xampp/htdocs/`.
4. **Configure o banco de dados**:
   - Abra `config.php` e ajuste `DB_HOST` (ex: '127.0.0.1:3307'), `DB_USER` ('root'), `DB_PASS` (senha do MySQL, geralmente vazia no XAMPP).
   - O banco `gestao_escolar` será criado automaticamente na primeira execução.
5. **Acesse o sistema**: Abra o navegador e vá para `http://localhost/BJGestaoEscolar-main/`.
6. **Login inicial**:
   - **Usuário root**: username `root`, senha `rootpass` (criado automaticamente).
   - **Usuário admin**: username `admin`, senha `admin123` (opcional).

O sistema inicializa o banco e cria uma escola de exemplo na primeira conexão.

### Migrações e Atualizações
- O script `db.php` inclui migrações automáticas para adicionar colunas (ex: `email`, `escola_id`) se não existirem.
- Tabelas são criadas com chaves estrangeiras para integridade referencial.

---

## 📖 Como Usar

1. **Login**: Acesse `index.php` e faça login com as credenciais root.
2. **Dashboard**: Após login, o `dashboard.php` exibe tabs para:
   - **Escolas**: Cadastre e liste escolas.
   - **Professores**: Cadastre professores com email e associação a escola.
   - **Turmas**: Crie turmas com professor e escola.
   - **Alunos**: Cadastre alunos com email e turma.
   - **Laudos**: Crie relatórios para alunos (acesso restrito por role).
3. **CRUD Operations**: Use os formulários nas tabs para criar/editar. Remoções via botões de ação.
4. **Roles e Permissões**:
   - **Root/Diretor**: Acesso total.
   - **Professor/Tutor**: Limitado a laudos e notas.
   - **Aluno/Responsável**: Visualização básica (implementar views específicas se necessário).
5. **Notas e Relatórios**: Use o módulo `notas/` para lançar notas e gerar relatórios simples.

Para funcionalidades avançadas (ex: edição), acesse subpastas como `alunos/editar.php`.

---
## 📌 Próximos Passos / TODO

- Implementar edição completa (ex: editar_aluno.php em todas as entidades)
- Adicionar módulo de presença/frequência integrado
- Melhorar relatórios com gráficos (usar Chart.js)
- Integração com email para notificações (usar PHPMailer)
- Responsividade mobile e temas dark/light
- Testes unitários para CRUD operations
- Migração para framework PHP (ex: Laravel) para escalabilidade

Consulte `TODO.md` para tarefas pendentes.

---

## ⚠️ Aviso de Uso

Este projeto é de **uso educacional e restrito**.  
Não é permitido copiar, modificar ou distribuir o código sem autorização prévia do autor.  
Para contribuições, abra issues no repositório Git (se disponível).

---

## 📞 Suporte

- **Autor**: [Seu Nome ou Contato]
- **Versão**: 2.0 (PHP Rewrite)
- **Licença**: MIT (para uso não-comercial)

Obrigado por usar BJGestaoEscolar! 🎓
=======
# 📚 BJGestaoEscolar

Sistema de **gestão escolar completo**, desenvolvido em **Python (Flask)** com integração a banco de dados.  
O projeto tem como objetivo oferecer uma solução prática para escolas, possibilitando o gerenciamento de alunos, professores, turmas, notas, presença e relatórios.
>>>>>>> meu_branch_backup

---

## 🚀 Funcionalidades

<<<<<<< HEAD
- 👩‍🏫 Cadastro e gerenciamento de **professores**, **diretores**, **tutores** e **responsáveis**
- 👨‍🎓 Cadastro e gerenciamento de **alunos** com associação a turmas
- 🏫 Criação e administração de **escolas** e **turmas** (com anos letivos e professores responsáveis)
- 📊 Lançamento de **notas** e **laudos** (relatórios acadêmicos/comportamentais)
- 📅 Controle de **frequência** e **médias** (implementação básica via notas)
- 🔒 Sistema de **autenticação** com roles (root, diretor, professor, tutor, aluno, responsável)
- 📝 Emissão de **relatórios** simples via dashboard
- ⚡ Interface responsiva com **Bootstrap 5** e animações CSS
=======
- 👩‍🏫 Cadastro e gerenciamento de **professores**
- 👨‍🎓 Cadastro e gerenciamento de **alunos**
- 🏫 Criação e administração de **turmas**
- 📊 Lançamento e cálculo automático de **médias de notas**
- 📅 Controle de **frequência/ presença**
- 📝 Emissão de relatórios acadêmicos
- 🔒 Sistema de **login com autenticação**
- 🗄 Integração com banco de dados (**PostgreSQL**)
>>>>>>> meu_branch_backup

---

## 🛠 Tecnologias Utilizadas

<<<<<<< HEAD
- **PHP 8+** (backend com PDO para banco de dados)
- **MySQL** (banco de dados relacional)
- **Bootstrap 5** (frontend e UI responsiva)
- **HTML5, CSS3 e JavaScript** (com animações e AJAX para interações dinâmicas)
- **XAMPP** (ambiente local de desenvolvimento e teste)
- **Session-based Authentication** (sem frameworks externos para simplicidade)

---

## 📦 Estrutura do Projeto

```
BJGestaoEscolar-main/
├── index.php              # Página de login
├── dashboard.php          # Dashboard principal com tabs para gerenciamento
├── db.php                 # Conexão e inicialização do banco (PDO)
├── config.php             # Configurações de DB e constantes
├── auth.php               # Funções de autenticação e roles
├── salvar_*.php           # Scripts de inserção (ex: salvar_aluno.php)
├── remover_*.php          # Scripts de remoção (ex: remover_aluno.php)
├── ajax/                  # Endpoints AJAX (ex: get_alunos_by_turma.php)
├── alunos/                # Módulo de alunos (cadastrar.php, listar.php, etc.)
├── professores/           # Módulo de professores
├── turmas/                # Módulo de turmas
├── notas/                 # Módulo de notas (lancar.php, relatorio.php)
├── static/                # Assets estáticos (CSS, JS, imagens)
├── assets/                # Assets adicionais (CSS, JS, imagens)
└── README.md              # Este arquivo
```

O banco de dados é inicializado automaticamente na primeira conexão, criando tabelas como `usuarios`, `escolas`, `professores`, `alunos`, `turmas`, `laudos`, `notas`, etc.

---

## 🚀 Instalação e Configuração

### Pré-requisitos
- **XAMPP** (com Apache e MySQL/MariaDB ativados)
- PHP 8+ e MySQL 5.7+ (incluídos no XAMPP)
- Editor de código (VS Code recomendado)

### Passos de Instalação
1. **Baixe e instale o XAMPP**: Acesse [xampp.apache.org](https://www.apachefriends.org/) e instale a versão para Windows.
2. **Inicie os serviços**: Abra o XAMPP Control Panel e inicie Apache e MySQL (porta 3307 para MySQL se conflitar com outra instalação).
3. **Coloque os arquivos no htdocs**: Copie a pasta `BJGestaoEscolar-main` para `c:/xampp/htdocs/`.
4. **Configure o banco de dados**:
   - Abra `config.php` e ajuste `DB_HOST` (ex: '127.0.0.1:3307'), `DB_USER` ('root'), `DB_PASS` (senha do MySQL, geralmente vazia no XAMPP).
   - O banco `gestao_escolar` será criado automaticamente na primeira execução.
5. **Acesse o sistema**: Abra o navegador e vá para `http://localhost/BJGestaoEscolar-main/`.
6. **Login inicial**:
   - **Usuário root**: username `root`, senha `rootpass` (criado automaticamente).
   - **Usuário admin**: username `admin`, senha `admin123` (opcional).

O sistema inicializa o banco e cria uma escola de exemplo na primeira conexão.

### Migrações e Atualizações
- O script `db.php` inclui migrações automáticas para adicionar colunas (ex: `email`, `escola_id`) se não existirem.
- Tabelas são criadas com chaves estrangeiras para integridade referencial.

---

## 📖 Como Usar

1. **Login**: Acesse `index.php` e faça login com as credenciais root.
2. **Dashboard**: Após login, o `dashboard.php` exibe tabs para:
   - **Escolas**: Cadastre e liste escolas.
   - **Professores**: Cadastre professores com email e associação a escola.
   - **Turmas**: Crie turmas com professor e escola.
   - **Alunos**: Cadastre alunos com email e turma.
   - **Laudos**: Crie relatórios para alunos (acesso restrito por role).
3. **CRUD Operations**: Use os formulários nas tabs para criar/editar. Remoções via botões de ação.
4. **Roles e Permissões**:
   - **Root/Diretor**: Acesso total.
   - **Professor/Tutor**: Limitado a laudos e notas.
   - **Aluno/Responsável**: Visualização básica (implementar views específicas se necessário).
5. **Notas e Relatórios**: Use o módulo `notas/` para lançar notas e gerar relatórios simples.

Para funcionalidades avançadas (ex: edição), acesse subpastas como `alunos/editar.php`.

---

## 🌐 Deploy Online (Opcional)

- **Heroku ou similar**: Não suportado nativamente (sem Procfile como na versão Python). Use hospedagens PHP como Hostinger ou 000webhost.
- Configure o MySQL remoto no `config.php` e suba os arquivos via FTP.
- Nota: A versão PHP é otimizada para ambientes locais; para produção, adicione HTTPS e proteções contra SQL injection (já mitigado via PDO).

---

## 📌 Próximos Passos / TODO

- Implementar edição completa (ex: editar_aluno.php em todas as entidades)
- Adicionar módulo de presença/frequência integrado
- Melhorar relatórios com gráficos (usar Chart.js)
- Integração com email para notificações (usar PHPMailer)
- Responsividade mobile e temas dark/light
- Testes unitários para CRUD operations
- Migração para framework PHP (ex: Laravel) para escalabilidade

Consulte `TODO.md` para tarefas pendentes.
=======
- **Python 3.x**
- **Flask** (framework web)
- **PostgreSQL** (banco de dados)
- **SQLAlchemy** (ORM)
- **HTML5, CSS3 e Bootstrap** (frontend)
- **Render** (deploy)

---

## 🌐 Acesse o Sistema

O sistema já está disponível online:  
👉 [https://bjgestaoescolar.onrender.com/](https://bjgestaoescolar.onrender.com/)  

---

## 📌 Próximos Passos

- Implementar dashboard com gráficos (notas e presença)  
- Adicionar envio de notificações para responsáveis  
- Melhorar a responsividade do frontend  
>>>>>>> meu_branch_backup

---

## ⚠️ Aviso de Uso

<<<<<<< HEAD
Este projeto é de **uso educacional e restrito**.  
Não é permitido copiar, modificar ou distribuir o código sem autorização prévia do autor.  
Para contribuições, abra issues no repositório Git (se disponível).

---

## 📞 Suporte

- **Autor**: [Seu Nome ou Contato]
- **Versão**: 2.0 (PHP Rewrite)
- **Licença**: MIT (para uso não-comercial)

Obrigado por usar BJGestaoEscolar! 🎓
=======
Este projeto é de **uso restrito**.  
Não é permitido copiar, modificar ou distribuir o código sem autorização prévia do autor.
>>>>>>> 35e54a3 (Primeiro commit do projeto)
>>>>>>> meu_branch_backup
