<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_Dgm_Tareos_TransferirTareo_V2');

        $procedure = "CREATE PROCEDURE sp_Dgm_Tareos_TransferirTareo_V2(
            @jsonTareos NVARCHAR(MAX)
        )
        AS
        BEGIN
        
        IF OBJECT_ID('TEMPDB..##TempTareos') IS NOT NULL DROP TABLE ##TempTareos
        IF OBJECT_ID('TEMPDB..#TempTareosDetalle') IS NOT NULL DROP TABLE ##TempTareosDetalle
        
        CREATE TABLE ##TempTareos(
            IdEmpresa varchar(2) NOT NULL,
            Id varchar(12) NOT NULL,
            Fecha date NOT NULL,
            Anio varchar(4) NOT NULL,
            Periodo varchar(6) NULL,
            Semana varchar(2) NULL,
            NroTareo varchar(5) NOT NULL,
            IdTurno char(1) NOT NULL,
            IdEstado varchar(3) NOT NULL,
            IdUsuarioCrea varchar(50) NULL,
            FechaHoraCreacion datetime NULL,
            IdUsuarioActualiza varchar(50) NULL,
            FechaHoraActualizacion datetime NULL,
            FechaHoraTransferencia datetime NULL,
            TotalHoras numeric(18, 2) NULL,
            TotalRendimientos numeric(18, 2) NULL,
            TotalDetalles int NULL,
            Observaciones varchar(max) NULL,
            OldId varchar(12) NOT NULL,
        )
        CREATE TABLE ##TempTareosReturn(
            IdEmpresa varchar(2) NOT NULL,
            Id varchar(12) NOT NULL,
            Fecha date NOT NULL,
            Anio varchar(4) NOT NULL,
            Periodo varchar(6) NULL,
            Semana varchar(2) NULL,
            NroTareo varchar(5) NOT NULL,
            IdTurno char(1) NOT NULL,
            IdEstado varchar(3) NOT NULL,
            IdUsuarioCrea varchar(50) NULL,
            FechaHoraCreacion datetime NULL,
            IdUsuarioActualiza varchar(50) NULL,
            FechaHoraActualizacion datetime NULL,
            FechaHoraTransferencia datetime NULL,
            TotalHoras numeric(18, 2) NULL,
            TotalRendimientos numeric(18, 2) NULL,
            TotalDetalles int NULL,
            Observaciones varchar(max) NULL,
            OldId varchar(12) NOT NULL,
            message VARCHAR(MAX) NULL
        )
        CREATE TABLE ##TempTareosDetalle(
            IdEmpresa varchar(2) NOT NULL,
            IdTareo varchar(12) NOT NULL,
            Item smallint NOT NULL,
            Dni varchar(8) NULL,
            IdPlanilla varchar(3) NULL,
            IdConsumidor varchar(20) NULL,
            IdCultivo varchar(10) NOT NULL,
            IdVariedad varchar(10) NOT NULL,
            IdActividad varchar(10) NULL,
            IdLabor varchar(10) NULL,
            SubTotalHoras numeric(18, 2) NULL,
            SubTotalRendimiento numeric(18, 2) NULL,
            Observacion varchar(max) NULL
        )
        -- ANALIZAR EL JSON ANIDADO E INSERTAR LA INFORMACIÓN DE LA CABECERA DE TAREOS
        INSERT INTO ##TempTareos
        SELECT
            IdEmpresa,
            CASE
                WHEN (select count(*) from trx_Tareos t where t.Id = jsonElement.Id) = 0 THEN
                    jsonElement.id
                WHEN (select count(*) from trx_Tareos t where t.Id = jsonElement.Id) > 0 THEN
                    DBO.funSiguienteCorrelativo((SELECT MAX(t.Id) FROM trx_Tareos t WHERE LEFT(t.Id,3) = LEFT(jsonElement.Id,3) ), 'A')
            END As Id,
            Fecha,
            YEAR(Fecha),
            Periodo,
            Semana,
            CASE
                WHEN (SELECT FORMAT(MAX(NroTareo) + 1, '00000') FROM trx_Tareos WHERE LEFT(jsonElement.Id, 3) = LEFT(trx_Tareos.Id, 3)) IS NULL
                    THEN '00001'
                ELSE (SELECT FORMAT(MAX(NroTareo) + 1, '00000') FROM trx_Tareos WHERE LEFT(jsonElement.Id, 3) = LEFT(trx_Tareos.Id, 3))
            END
            AS NroTareo,
            IdTurno,
            'PE' AS IdEstado,
            IdUsuarioCrea,
            FechaHoraCreacion,
            IdUsuarioActualiza,
            FechaHoraActualizacion,
            GETDATE(),
            TotalHoras,
            TotalRendimientos,
            TotalDetalles,
            Observaciones,
            Id
        FROM (
            SELECT
                JSON_VALUE(jsonElement.value, '$.IdEmpresa') AS IdEmpresa,
                JSON_VALUE(jsonElement.value, '$.Id') AS Id,
                JSON_VALUE(jsonElement.value, '$.Fecha') AS Fecha,
                JSON_VALUE(jsonElement.value, '$.Anio') AS Anio,
                JSON_VALUE(jsonElement.value, '$.Periodo') AS Periodo,
                JSON_VALUE(jsonElement.value, '$.Semana') AS Semana,
                JSON_VALUE(jsonElement.value, '$.IdTurno') AS IdTurno,
                JSON_VALUE(jsonElement.value, '$.IdUsuarioCrea') AS IdUsuarioCrea,
                JSON_VALUE(jsonElement.value, '$.FechaHoraCreacion') AS FechaHoraCreacion,
                JSON_VALUE(jsonElement.value, '$.IdUsuarioActualiza') AS IdUsuarioActualiza,
                JSON_VALUE(jsonElement.value, '$.FechaHoraActualizacion') AS FechaHoraActualizacion,
                JSON_VALUE(jsonElement.value, '$.FechaHoraTransferencia') AS FechaHoraTransferencia,
                JSON_VALUE(jsonElement.value, '$.TotalHoras') AS TotalHoras,
                JSON_VALUE(jsonElement.value, '$.TotalRendimientos') AS TotalRendimientos,
                JSON_VALUE(jsonElement.value, '$.TotalDetalles') AS TotalDetalles,
                JSON_VALUE(jsonElement.value, '$.Observaciones') AS Observaciones                
            FROM OPENJSON(@jsonTareos, '$.tareos') AS jsonElement
        
        ) AS jsonElement;
         -- ANALIZAR EL JSON ANIDADO E INSERTAR LA INFORMACIÓN DE LOS DETALLES DE TAREOS
        
        INSERT INTO ##TempTareosDetalle
        SELECT 
            IdEmpresa,
            Id,
            detalle.Item,
            detalle.Dni,
            detalle.IdPlanilla,
            detalle.IdConsumidor,
            ISNULL(detalle.IdCultivo, '') AS IdCultivo,
            ISNULL(detalle.IdVariedad, '') AS IdVariedad,
            detalle.IdActividad,
            detalle.IdLabor,
            detalle.SubTotalHoras,
            detalle.SubTotalRendimiento,
            detalle.Observacion
        FROM 
            OPENJSON(@jsonTareos, '$.tareos') 
        WITH (
            IdEmpresa NVARCHAR(50),
            Id NVARCHAR(50),
            detalles NVARCHAR(MAX) AS JSON
        )
        CROSS APPLY OPENJSON(detalles) 
        WITH (
            Item smallint,
            Dni varchar(8) ,
            IdPlanilla varchar(3),
            IdConsumidor varchar(20),
            IdCultivo varchar(10),
            IdVariedad varchar(10),
            IdActividad varchar(10),
            IdLabor varchar(10),
            SubTotalHoras numeric(18, 2),
            SubTotalRendimiento numeric(18, 2),
            Observacion varchar(max)
        ) AS detalle;
        
        
        
        -------------------------------------------------------------------------------------- INICIO DE CURSOR PARA trx_Tareos
        -- Declarar un cursor para la tabla
        DECLARE curTabla CURSOR FOR
        SELECT IdEmpresa, Id, Fecha, Anio, Periodo, Semana, NroTareo, IdTurno, IdEstado, IdUsuarioCrea, 
               FechaHoraCreacion, IdUsuarioActualiza, FechaHoraActualizacion, FechaHoraTransferencia,
               TotalHoras, TotalRendimientos, TotalDetalles, Observaciones, OldId
        FROM ##TempTareos;
        -- Declarar variables para almacenar los valores de la fila actual
        DECLARE @IdEmpresa VARCHAR(10), @Id VARCHAR(20), @Fecha DATE, @Anio INT, @Periodo INT, @Semana INT, 
                @NroTareo VARCHAR(10), @IdTurno VARCHAR(10), @IdEstado VARCHAR(10), @IdUsuarioCrea VARCHAR(20), 
                @FechaHoraCreacion DATETIME, @IdUsuarioActualiza VARCHAR(20), @FechaHoraActualizacion DATETIME, 
                @FechaHoraTransferencia DATETIME, @TotalHoras DECIMAL(10,2), @TotalRendimientos DECIMAL(10,2), 
                @TotalDetalles INT, @Observaciones VARCHAR(MAX), @OldId VARCHAR(20);
        
        -- Abrir el cursor
        OPEN curTabla;
        
        -- Inicializar la variable que indica si hay más filas
        FETCH NEXT FROM curTabla INTO @IdEmpresa, @Id, @Fecha, @Anio, @Periodo, @Semana, @NroTareo, @IdTurno, @IdEstado,
                                     @IdUsuarioCrea, @FechaHoraCreacion, @IdUsuarioActualiza, @FechaHoraActualizacion,
                                     @FechaHoraTransferencia, @TotalHoras, @TotalRendimientos, @TotalDetalles,
                                     @Observaciones, @OldId;
        
        -- -- Bucle para recorrer fila por fila
        WHILE @@FETCH_STATUS = 0
        BEGIN
            DECLARE @idInsert VARCHAR(12)
            SET @idInsert = CASE
                    WHEN (select count(*) from trx_Tareos t where t.Id = @Id) = 0 THEN
                        @Id
                    WHEN (select count(*) from trx_Tareos t where t.Id = @Id) > 0 THEN
                        DBO.funSiguienteCorrelativo((SELECT MAX(t.Id) FROM trx_Tareos t WHERE LEFT(t.Id,3) = LEFT(@Id,3) ), 'A')
                END
        --     -- Realizar comparativa o cualquier otra operación que necesites aquí
        --     -- Por ejemplo, puedes imprimir valores para propósitos de prueba
        
            update ##TempTareos set
                Id = @idInsert
            INSERT INTO DataGreenMovil..trx_Tareos
                SELECT
                    @IdEmpresa,
                    @idInsert As Id
                    ,@Fecha, YEAR(@Fecha), @Periodo, @Semana,
                    (select ISNULL(FORMAT(MAX(nrotareo) + 1,'00000'), '00001') from trx_Tareos WHERE LEFT(Id,3) = LEFT(@idInsert,3)),
                    @IdTurno,
                    @IdEstado,
                    @IdUsuarioCrea,
                    @FechaHoraCreacion,
                    @IdUsuarioActualiza,
                    @FechaHoraActualizacion,
                    GETDATE(),
                    @TotalHoras,
                    @TotalRendimientos,
                    @TotalDetalles,
                    @Observaciones;
            
            INSERT INTO ##TempTareosReturn
                SELECT
                    @IdEmpresa,
                @idInsert As Id
                ,@Fecha,
                YEAR(@Fecha),
                @Periodo,
                @Semana,
                (select ISNULL(FORMAT(MAX(nrotareo) + 1,'00000'), '00001') from trx_Tareos),
                @IdTurno,
                @IdEstado,
                @IdUsuarioCrea,
                @FechaHoraCreacion,
                @IdUsuarioActualiza,
                @FechaHoraActualizacion,
                GETDATE(),
                @TotalHoras,
                @TotalRendimientos,
                @TotalDetalles,
                @Observaciones,
                @OldId,
                CASE
                    WHEN @idInsert = @OldId THEN
                        'Tareos ingresados con éxito.!'
                    ELSE
                        'Se han actualizado algunos identificadores en las tablas locales.'
                END;
        
        --     -- Obtener la siguiente fila
            FETCH NEXT FROM curTabla INTO @IdEmpresa, @Id, @Fecha, @Anio, @Periodo, @Semana, @NroTareo, @IdTurno, @IdEstado,
                                         @IdUsuarioCrea, @FechaHoraCreacion, @IdUsuarioActualiza, @FechaHoraActualizacion,
                                         @FechaHoraTransferencia, @TotalHoras, @TotalRendimientos, @TotalDetalles,
                                         @Observaciones, @OldId;
        END
        
        -- Cerrar el cursor
        CLOSE curTabla;
        DEALLOCATE curTabla;
        
        -------------------------------------------------------------------------------------- INICIO DE CURSOR PARA trx_Tareos_Detalle
        -- Declarar un cursor para la tabla
        DECLARE curTareosDetalle CURSOR FOR
        SELECT IdEmpresa, IdTareo, Item, Dni, IdPlanilla, IdConsumidor, IdCultivo, IdVariedad, IdActividad, IdLabor, SubTotalHoras, SubTotalRendimiento, Observacion
        FROM ##TempTareosDetalle;
        -- Declarar variables para almacenar los valores de la fila actual
        DECLARE @td_IdEmpresa varchar(2),
                @td_IdTareo varchar(12),
                @td_Item smallint,
                @td_Dni varchar(8),
                @td_IdPlanilla varchar(3),
                @td_IdConsumidor varchar(20),
                @td_IdCultivo varchar(10),
                @td_IdVariedad varchar(10),
                @td_IdActividad varchar(10),
                @td_IdLabor varchar(10),
                @td_SubTotalHoras numeric(18, 2),
                @td_SubTotalRendimiento numeric(18, 2),
                @td_Observacion varchar(max)
        
        -- Abrir el cursor
        OPEN curTareosDetalle;
        
        -- Inicializar la variable que indica si hay más filas
        FETCH NEXT FROM curTareosDetalle INTO
                @td_IdEmpresa,
                @td_IdTareo,
                @td_Item,
                @td_Dni,
                @td_IdPlanilla,
                @td_IdConsumidor,
                @td_IdCultivo,
                @td_IdVariedad,
                @td_IdActividad,
                @td_IdLabor,
                @td_SubTotalHoras,
                @td_SubTotalRendimiento,
                @td_Observacion
        
        -- -- Bucle para recorrer fila por fila
        WHILE @@FETCH_STATUS = 0
        BEGIN
        --     -- Realizar comparativa o cualquier otra operación que necesites aquí
        --     -- Por ejemplo, puedes imprimir valores para propósitos de prueba
        
        -- select * FROM ##TempTareosDetalle;
            INSERT INTO DataGreenMovil..trx_Tareos_Detalle
                SELECT @td_IdEmpresa,
                (SELECT Id from ##TempTareosReturn where OldId = @td_IdTareo) As IdTareo,
                @td_Item,
                @td_Dni,
                @td_IdPlanilla,
                @td_IdConsumidor,
                @td_IdCultivo,
                @td_IdVariedad,
                @td_IdActividad,
                @td_IdLabor,
                @td_SubTotalHoras,
                @td_SubTotalRendimiento,
                @td_Observacion
        
        --     -- Obtener la siguiente fila
            FETCH NEXT FROM curTareosDetalle INTO
                @td_IdEmpresa,
                @td_IdTareo,
                @td_Item,
                @td_Dni,
                @td_IdPlanilla,
                @td_IdConsumidor,
                @td_IdCultivo,
                @td_IdVariedad,
                @td_IdActividad,
                @td_IdLabor,
                @td_SubTotalHoras,
                @td_SubTotalRendimiento,
                @td_Observacion
        END
        
        -- Cerrar el cursor
        CLOSE curTareosDetalle;
        DEALLOCATE curTareosDetalle;
        
        IF (select count(*) from ##TempTareosReturn where Id = OldId) > 0
            select message, Id As ReplaceId, OldId, FechaHoraTransferencia from ##TempTareosReturn
        ELSE
            select message, Id ReplaceId, OldId, FechaHoraTransferencia from ##TempTareosReturn
        
        -- select * from  ##TempTareos
        -- select * from  ##TempTareosDetalle
        -- select * from  ##TempTareosReturn
        
        END";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
