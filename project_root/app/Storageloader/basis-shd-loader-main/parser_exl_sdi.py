#!/usr/bin/python3

from BasisAPI2 import API as BasisAPI
import logging, re, openpyxl, json, click


if __name__ == '__main__':
    @click.command()
    @click.option("--xlsxname", "-xlsx", default = "NetAPP_1.xlsx", required = True, type = str, help = "Xlsx file name")
    @click.option("--xlsxsheetname", "-shname", default = "Test sheet", required = True, type = str, help = "Xlsx sheet name")
    @click.option("--jsonname", "-json", default = "disks.json", required = True, type = str, help = "Json file name")
    def start(xlsxname, xlsxsheetname, jsonname):
        hddParams = {
            "Status": "Status",
            "Mode": "Mode",
            "Raw capacity": "rawSize",
            "Usable capacity": "useSize",
            "World-wide identifier": "wwId",
            "Associated disk pool": "diskPool",
            "Media type": "type",
            "Interface type": "intType",
            "Drive path redundancy": "redundancy",
            "Drive capabilities": "driveCapabilities",
            "Security capable": "secCapabilities",
            "Secure": "Secure",
            "Read/write accessible": "rw",
            "Drive security key identifier": "secKey",
            "Data Assurance (DA) capable": "daCapabilities",
            "Speed": "Speed",
            "Current data rate": "dataRate",
            "Logical sector size": "lSectorSize",
            "Physical sector size": "pSectorSize",
            "Product ID": "productId",
            "Drive firmware version": "fwVersion",
            "Serial number": "sn",
            "Manufacturer": "manName",
            "Date of manufacture": "manDate"
        }

        logger = logging.getLogger(__name__)

        def readfile(path, sheet_name):
            wb = openpyxl.load_workbook(path)
            sheet = wb[sheet_name]
            data = []
            for row in sheet.values:
                data.append(row)
            return data

        # Читаем xlsx с данными о дисках
        data = []
        data = readfile(f"/opt/SDI_Reader_2/basis-shd-loader/{xlsxname}", xlsxsheetname)

        hdd = {}
        tray = ""
        hddKey = ""

        pattern = re.compile("Drive at Tray \d+, Drawer \d+, Slot \d+")

        for i in range(len(data)):
            # Ищем начало записи о диске
            #        print (data[i][0])
            if pattern.match(data[i][0].strip()):
                # Нашли начало записи о диске
                digit = re.findall(r"\d+", data[i][0])
                #            print('Yes = ',data[i][0])
                tray = digit[0]
                drawer = digit[1]
                slot = digit[2]
                if not tray in hdd.keys():
                    hdd[tray] = {}
                hddKey = tray + "-" + drawer + "-" + slot
                hdd[tray][hddKey] = {}
                hdd[tray][hddKey]["slot"] = hddKey
            if not data[i][1]:
                continue
            if not hddParams[data[i][0].strip()]:
                continue
            param = hddParams[data[i][0].strip()]
            # Преобразуем данные
            if param == "rawSize" or param == "useSize":
                hdd[tray][hddKey][param] = str(data[i][1]).replace("GB", "").replace(u"\u00A0", "").strip()
            elif param == "type":
                if str(data[i][1]).strip() == "Hard Disk Drive":
                    hdd[tray][hddKey][param] = "HDD"
                else:
                    hdd[tray][hddKey][param] = "SSD"
            elif param == "intType":
                if str(data[i][1]).strip() == "Serial Attached SCSI (SAS)":
                    hdd[tray][hddKey][param] = "SAS"
                else:
                    hdd[tray][hddKey][param] = ""
            elif param == "driveCapabilities":
                if str(data[i][1]).strip() == "Data Assurance (DA)":
                    hdd[tray][hddKey][param] = "DA"
                else:
                    hdd[tray][hddKey][param] = str(data[i][1]).strip()
            elif param == "Speed":
                hdd[tray][hddKey][param] = str(data[i][1]).replace("RPM", "").replace(u"\u00A0", "").strip()
            elif param == "lSectorSize":
                hdd[tray][hddKey][param] = str(data[i][1]).replace("bytes", "").replace(u"\u00A0", "").strip()
            elif param == "pSectorSize":
                hdd[tray][hddKey][param] = str(data[i][1]).replace("bytes", "").replace(u"\u00A0", "").strip()
            elif param == "dataRate":
                hdd[tray][hddKey][param] = str(data[i][1]).replace("Gbps", "").replace(u"\u00A0", "").strip()
            else:
                hdd[tray][hddKey][param] = str(data[i][1]).strip()
        # JSON для заливки HDD в полки готов
        # Готовим список HDDкоторые нужно добавить в СДИ
        # Получаем полный список HDD в СДИ

        with open(f'/opt/SDI_Reader_2/basis-shd-loader/{jsonname}', 'w') as file:
            json.dump(hdd, file, indent=4, ensure_ascii=True)

    start()