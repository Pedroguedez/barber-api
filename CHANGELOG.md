# Changelog

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

- Melhorias contínuas na cobertura de testes automatizados
- Ajustes de layout e organização de arquivos
- Refatoração incremental da linguagem (pt → en)
- Correções de status e nome de campos no banco
- Refinamento do fluxo de validação com Request e Services

---
## [v1.2.1] - 2025-05-22

### Adicionado
- Análise de código com **PHPStan** no nível máximo (`--level=max`)
- Verificação completa de conformidade com **PSR-12** via **PHP_CodeSniffer (phpcs)**

### Corrigido
- Correção de todos os erros reportados por `phpcs`, incluindo:
  - Abertura incorreta de tag PHP (`<?` → `<?php`)
  - Espaços incorretos em type hints e declarações de retorno
  - Uso de estruturas inline (`if`, `foreach`) fora do padrão PSR-12
- Automatização da correção com `phpcbf`

### Observações
- Todas as correções foram aplicadas automaticamente com `phpcbf --standard=PSR12 app/`
- Análise PHPStan executada com:  
  ```bash
  vendor/bin/phpstan analyse --level=max app/ --memory-limit=1G

## [v1.2.0] - 2025-05-21

### Adicionado
- Verificação de horários disponíveis com base na duração do serviço
- Alteração dinâmica do `service_id` durante a verificação de agendamento
- Testes para validação de horários disponíveis e integração com agendamentos

### Modificado
- Refatoração do controller de horários para seguir SRP
- Ajustes no fluxo de criação de agendamentos com o uso de `AppointmentBuilder`

---

## [v1.1.1] - 2025-05-20

### Adicionado
- Criação da classe `AppointmentBuilder` em `app/Builders`
- Implementação do padrão Builder para construção fluente de agendamentos
- Testes para store de horário de funcionamento
- Refatoração seguindo o SRP nos módulos `User`, `WorkingHour` e `Appointment`

### Corrigido
- Correções nos campos `status`, `nome` e padronização da linguagem (português → inglês)
- Ajustes nas Requests e validações desacopladas

---

## [v1.1.0] - 2025-05-18

### Adicionado
- Testes automatizados para agendamentos
- Integração do PHPStan e PHPCS para análise estática e padronização de código
- API para gerenciamento de horários de funcionamento

---

## [v1.0.1] - 2025-05-11

### Adicionado
- CRUD completo para serviços com validação e testes
- API RESTful para gerenciamento de agendamentos e serviços

---

## [v1.0.0] - 2025-05-08

### Adicionado
- Inicialização do projeto com autenticação e CRUD de usuários
- Estrutura básica de API utilizando Laravel
- Migrações e modelos Eloquent: `User`, `Service`, `Appointment`, `WorkingHour`
- Implementação do login e controle de acesso via Sanctum
