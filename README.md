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

## 📋 Changelog

Consulte o [CHANGELOG.md](CHANGELOG.md) para um histórico detalhado de todas as alterações e versões do projeto.

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