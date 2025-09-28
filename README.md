
O banco de dados é inicializado automaticamente na primeira conexão, criando tabelas como `usuarios`, `escolas`, `professores`, `alunos`, `turmas`, `laudos`, `notas`, etc.

---

## 🚀 Instalação e Configuração

### Pré-requisitos
- **XAMPP** (com Apache e MySQL/MariaDB ativados)
- PHP 8+ e MySQL 5.7+ (incluídos no XAMPP)
- Editor de código (VS Code recomendado)

### Passos de Instalação
1. **Baixe e instale o XAMPP**: Acesse [xampp.apache.org](https://www.apachefriends.org/) e instale a versão para Windows.
2. **Inicie os serviços**: Abra o XAMPP Control Panel e inicie Apache e MySQL.
3. **Coloque os arquivos no htdocs**: Copie a pasta `BJGestaoEscolar-main` para `c:/xampp/htdocs/`.
4. **Configure o banco de dados**:
   - Abra `config.php` e ajuste `DB_HOST`, `DB_USER` e `DB_PASS`.
   - O banco `gestao_escolar` será criado automaticamente na primeira execução.
5. **Acesse o sistema**: Abra o navegador e vá para `http://localhost/BJGestaoEscolar-main/`.
6. **Login inicial**:
   - **Usuário root**: username `root`, senha `rootpass`.
   - **Usuário admin**: username `admin`, senha `admin123` (opcional).

### Migrações e Atualizações
- O script `db.php` inclui migrações automáticas para adicionar colunas (ex: `email`, `escola_id`) se não existirem.
- Tabelas são criadas com chaves estrangeiras para integridade referencial.

---

## 📖 Como Usar

1. **Login**: Acesse `index.php` e faça login com as credenciais root.
2. **Dashboard**: Após login, o `dashboard.php` exibe tabs para:
   - **Escolas**, **Professores**, **Turmas**, **Alunos**, **Laudos**
3. **CRUD Operations**: Use os formulários nas tabs para criar/editar. Remoções via botões de ação.
4. **Roles e Permissões**:
   - **Root/Diretor**: Acesso total.
   - **Professor/Tutor**: Limitado a laudos e notas.
   - **Aluno/Responsável**: Visualização básica.
5. **Notas e Relatórios**: Use o módulo `notas/` para lançar notas e gerar relatórios simples.

---

## 🌐 Deploy Online (Opcional)

- Use hospedagens PHP como Hostinger ou 000webhost.
- Configure o MySQL remoto no `config.php` e suba os arquivos via FTP.
- Para produção, adicione HTTPS e proteções adicionais.

---

## 📌 Próximos Passos / TODO

- Implementar edição completa em todas as entidades
- Adicionar módulo de presença/frequência
- Melhorar relatórios com gráficos (Chart.js)
- Integração com email para notificações (PHPMailer)
- Responsividade mobile e temas dark/light
- Testes unitários para CRUD operations
- Migração para framework PHP (Laravel) para escalabilidade

Consulte `TODO.md` para tarefas pendentes.

---

## ⚠️ Aviso de Uso

Este projeto é de **uso educacional e restrito**.  
Não é permitido copiar, modificar ou distribuir o código sem autorização prévia do autor.

---

## 📞 Suporte

- **Autor**: [Seu Nome ou Contato]  
- **Versão**: 2.0 (PHP Rewrite)  
- **Licença**: MIT (para uso não-comercial)

Obrigado por usar BJGestaoEscolar! 🎓
