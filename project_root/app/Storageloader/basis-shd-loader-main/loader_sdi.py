#!/usr/bin/python3

from BasisAPI2 import API as BasisAPI
from model import Drive, Storage, DriveList, VolumeList
import logging, json, click

logger = logging.getLogger(__name__)


if __name__ == '__main__':
    @click.command()
    @click.option("--ssname", "-ssname", default = "NetApp-2", required = True, type = str, help = "Storage System name")
    @click.option("--jsonname", "-json", default = "disks.json", required = True, type = str, help = "Json file name")
    @click.option("--host", "-url", default = "10.161.60.30", required = True, type = str, help = "IP basis")
    @click.option("--port", "-p", default = "8080", required = True, type = str, help = "Port basis")
    @click.option("--protocol", "-pr", default = "http", required = True, type = str, help = "Protocol")
    @click.option("--username", "-un", default = "sdi-api", required = True, type = str, help = "Basis username")
    @click.option("--password", "-pw", default = "basis", required = True, type = str, help = "Basis password")
    @click.option("--mandant", "-man", default = "1001", required = True, type = str, help = "Basis domen")
    @click.option("--group", "-gr", default = "sdi-api", required = True, type = str, help = "Basis group")
    def start(ssname, jsonname, host, port, protocol, username, password, mandant, group):
        basisApi = BasisAPI(url=host,
                        port=port,
                        protocol=protocol,
                        username=username,
                        password=password,
                        mandant=mandant,
                        group=group
                        )

        dcimDrives = basisApi.get_hard_drives()

        #Открываем JSON файл со списком оборудования
        with open(f"/opt/SDI_Reader_2/basis-shd-loader/{jsonname}", 'r') as file:
            hdd = json.loads(file.read())

        # for key in hdd.keys():
        #     data = ["slot", "sn", "useSize", "prodId", "type", "intType"]
        #     for value in hdd[key].keys():
        #         if value not in data:
        #             hdd[key].pop(value)

        # Читаем список полок для СХД
        ssElid = basisApi.getStorageSystemByName(ssname)

        print("Get Data for StorageSyatem: ", ssname, " => ", ssElid)
        diskArray = {}
        da = basisApi.getDiskArrayBySSElid(ssElid)

        for i in range(len(da)):
            diskArray[da[i]['cIdMetka']] = Storage(visibleID=da[i]['visibleId'], dcimElid=da[i]['elid'],
                                                cIdMetka=da[i]['cIdMetka'])

        for id in diskArray.keys():
            diskArray[id].dcimDrives = basisApi.get_hard_drives_from_storage(diskArray[id].dcimElid)

        dcimDrives = basisApi.get_hard_drives()

        # сопоставляем с JSON

        for tray in hdd.keys():
            for hddKey in hdd[tray].keys():
                if hdd[tray][hddKey]["sn"] in dcimDrives.keys():
                    hdd[tray][hddKey]["elid"] = dcimDrives[hdd[tray][hddKey]["sn"]]["elid"]
                else:
                    hdd[tray][hddKey]["elid"] = ""


        # сопоставляем hdd из JSON с hdd полок в DCIM
        for id in diskArray.keys():
            if hdd[id]:
                diskArray[id].monitoringDrives = hdd[id]
            else:
                diskArray[id].monitoringDrives = {}

        # создаем диски

        for tray in hdd.keys():
            for hddKey in hdd[tray].keys():
                if hdd[tray][hddKey]["elid"] == "":
                    print("!!! Создаем Диск в СДИ: ", hddKey)
                    ret = basisApi.create_hard_drive(hddName=ssname + "_" + hddKey, SN=hdd[tray][hddKey]['sn'],
                                                    manufacturer="",
                                                    size=hdd[tray][hddKey]['useSize'],
                                                    model=hdd[tray][hddKey]['productId'], type=hdd[tray][hddKey]['type'],
                                                    intType=hdd[tray][hddKey]['intType'], driveStatus=1)
                    if ret:
                        hdd[tray][hddKey]['elid'] = ret['elid']
                        print("!!!!!!! HDD created ", hddKey, " ", hdd[tray][hddKey]['elid'])
                        dcimDrives[hdd[tray][hddKey]['sn']] = {'elid': hdd[tray][hddKey]['elid']}
        #            else:
        #                print("***** Диск СУЩЕСТВУЕТ в СДИ: ",hddKey)

        # Теперь можно клеить диски к полкам
        for tray in diskArray.keys():
            for hddKey in hdd[tray].keys():
                print(diskArray[tray].dcimDrives)
                if not hdd[tray][hddKey]["sn"] in diskArray[tray].dcimDrives.keys():
                    print("Нужно подключить диск ", hddKey, " к полке ", tray)
                    linkElid = basisApi.link_hard_drive_to_storage(diskArray[tray].dcimElid, hdd[tray][hddKey]["elid"],
                                                                hddKey)
                    if linkElid:
                        # После добавления диска к полке, добавляем в список
                        diskArray[tray].dcimDrives[hdd[tray][hddKey]["sn"]] = {'linkElid': linkElid,
                                                                            'slotName': hdd[tray][hddKey]["slot"],
                                                                            'elid': hdd[tray][hddKey]["elid"]
                                                                            }

        # Теперь можно убрать лишние диски из полкам
        print("Убираем лишние диски")
        for tray in diskArray.keys():
            #        print("Для полки: ",tray," ",len(hdd[tray].keys()))
            for sn in diskArray[tray].dcimDrives.keys():
                match = len(hdd[tray].keys())
                for hddKey in hdd[tray].keys():
                    if not sn == hdd[tray][hddKey]["sn"]:
                        match = match - 1
                if match == 0:
                    print("Диск ", sn, " нужно убрать из СХД ", tray)
                    print(diskArray[tray].dcimElid)
                    print(diskArray[tray].dcimDrives[sn])
                    basisApi.unlink_hard_drive_from_storage(diskArray[tray].dcimElid,
                                                            diskArray[tray].dcimDrives[sn]['linkElid'])
                    
    start()