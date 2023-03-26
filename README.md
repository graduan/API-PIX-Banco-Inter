Acesso a API PIX do Banco Inter

API PIX

- COBRANÇA IMEDITA
-- pix_cobranca_criar		Criar cobrança imediata
-- pix_cobranca_revisar	Revisar cobrança imediata (não implementada)
-- pix_cobranca_consultar	Consultar cobrança imediata
-- pix_cobranca_criar_txid	Criar cobrança imediata (com txid criado pelo usuário)
-- pix_cobranca_listar		Consultar lista de cobranças imediatas

- LOCATION
-- pix_location_criar		Criar location do payload POST (não implementada)
-- pix_location_consultar	Consultar locations cadastradas GET (não implementada)
-- pix_location_payload		Recuperar location do payload GET (não implementada)
-- pix_location_desvincular	Desvincular uma cobrança de uma location DELETE (não implementada)

- PIX
-- pix_consultar		Consultar pix GET
-- pix_consultar_recebidos	Consultar pix recebidos GET
-- pix_devolucao		Solicitar devolução PUT
-- pix_consultar_devolucao	Consultar devolução GET

- WEBHOOK
-- pix_webhook_criar		Criar webhook PUT
-- pix_webhook_consultar	Obter webhook cadastrado GET
-- pix_webhook_excluir		Excluir webhook DELETE
