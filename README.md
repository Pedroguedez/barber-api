# Sistema de Agendamento para Barbearia

## ✂️ Descrição
Sistema RESTful para gerenciamento de agendamentos, horários de funcionamento, barbeiros e serviços. Desenvolvido em Laravel com foco em boas práticas de **Clean Code** e **Testes Automatizados**.

## ✅ Funcionalidades
- CRUD de agendamentos
- CRUD de horários de funcionamento
- CRUD de serviços
- Validação de funcionários como barbeiros
- API organizada por boas práticas de Clean Code
- Uso de Builder para criação fluente de agendamentos

## 🔍 Problemas Encontrados (antes da refatoração)
- Lógica duplicada em controllers
- Validações misturadas com regras de negócio
- Falta de testes automatizados
- Violações do princípio SRP (Single Responsibility Principle)

## 🛠 Estratégia de Refatoração
- Criação de **Services** para encapsular regras de negócio
- Extração de validações em classes próprias
- Aplicação de testes automatizados com PHPUnit
- Uso de **Interfaces Fluentes** (Builder Pattern)
- Padronização de nomes e migrações em inglês

## 📋 CHANGELOG.md
- [ADD] Refatoração completa dos controllers
- [ADD] Testes unitários e de integração com PHPUnit
- [ADD] Análise estática com PHPStan
- [ADD] Linter com PHP_CodeSniffer
- [ADD] `AppointmentBuilder` com padrão Builder
- [ADD] Renomeação das tabelas e campos para inglês
- [ADD] Padronização da arquitetura em camadas (Controller, Service, Builder, Request, Model)

## 🧪 Testes
- Executados com PHPUnit
- Cobertura de controllers e serviços
- Validação de criação de agendamentos com Builder fluente

## 🧩 Interface Fluente
Aplicada em:
- `AppointmentBuilder`: construção de agendamentos com encadeamento de métodos
- `HorarioValidator`: validações compostas para horários

### Exemplo de uso do `AppointmentBuilder`:

```php
$appointment = (new AppointmentBuilder())
    ->paraCliente($request->cliente_id)
    ->comBarbeiro($request->barber_id)
    ->comServico($request->service_id)
    ->naData($request->data)
    ->noHorario($request->time)
    ->comStatus('pending')
    ->comObservacao('Cliente pediu para não atrasar')
    ->criar();
▶️ Instalação

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
php artisan test