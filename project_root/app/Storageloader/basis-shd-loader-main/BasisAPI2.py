#!/usr/bin/python3

import requests, logging, json
import oracledb
from typing import Any, List, Dict 
from datetime import datetime

# from requests.packages.urllib3.exceptions import InsecureRequestWarning
# requests.packages.urllib3.disable_warnings(InsecureRequestWarning)
IMS_API_URL=''
logger = logging.getLogger(__name__)

class API:
    headers = {"Accept" : "application/json", 
                "Content-Type": "application/json", 
                "charset": "UTF-8"}
    
    def __init__(self,
                 url='127.0.0.1', 
                 port=80, 
                 protocol='https', 
                 username='Базис', 
                 password='basis', 
                 mandant=1001,
                 group='админ'
                 ) -> None:
        self.url=url
        self.port=str(port)
        self.protocol=protocol
        self.username=username
        self.password=password
        self.mandant=str(mandant)
        self.group=f'{group}|G'
        self.apiUrl=f'{self.protocol}://{self.url}:{self.port}/axis/api/rest'
        self.checking_sessionid()
        
        self.consist_of_body = {"relationRestrictions": {"role": {"value": "CONSISTS_OF", "operator": "="}},
                "entityRestrictions": {}, "returnRelationAttributes": [], "returnEntityAttributes": []}
            
    
    def login(self)->None:
        url=f'{self.apiUrl}/businessGateway/login'
        body=json.dumps({"user": self.username,
                     "password": self.password,
                     "manId": self.mandant,
                     "userGroupName": self.group
                      })
        try:
            response = requests.post(url,verify=False,headers=self.headers,data=body)
            if response.status_code==200:
                response_json=response.json()
                if 'sessionId' in response_json:
                    self.sessionId=response_json['sessionId']     
            else:
                logging.error(response.json())
        except Exception as e:
            logger.error(e)

    def logout(self)->None:
        url=f'{self.apiUrl}/businessGateway/logout?sessionId={self.sessionId}'
        body=dict()
        try:
            response = requests.post(url,verify=False,headers=self.headers,data=body)
            return response.status_code
        except Exception as e:
            logger.error(e)         

    def req(self,reqUrl,body)->None:
        url=f'{self.apiUrl}{reqUrl}?sessionId={self.sessionId}'
        try:
            response = requests.post(url,verify=False,headers=self.headers,data=json.dumps(body))
            if response.status_code==200:
                return response.json()['returnData'] if 'returnData' in response.json() else response.json()
            else:
                print(url)
                print(body)
                print(response.json())
                return None
                logger.warning(f'{body}{response.json()["status"]["message"]}')
                #error.log
        except Exception as e:
            logger.error(e)
            
    def checking_sessionid(self):
        try:
            with open(f"/tmp/basis/{self.mandant}@@sessionId", "r") as file:
                self.sessionId = file.read()
                file.close()
        except: self.sessionId = ""
        
        is_allive = self.req("/entity/session/isAlive", {})
        if is_allive != [{}]:
            self.login()
            with open(f"/tmp/basis/{self.mandant}@@sessionId", "w") as file:
                file.write(self.sessionId)
                file.close()

    def getStorageSystemByName(self,visibleId):
        url = '/entity/storageSystem/query'

        body={"restrictions":{"visibleId":{"value":visibleId,
                                       "operator":"=" }
                        }, "returnAttributes": [] }
        ret=self.req(url, body)
        elid=ret[0]['elid']
        return elid
    
    def getStorageByName(self,visibleId):
        url = '/entity/storage/query'

        body={"restrictions":{"visibleId":{"value":visibleId,
                                       "operator":"=" }
                        }, "returnAttributes": [] }
        ret=self.req(url, body)
        return ret[0]['elid']

    def get_controllers_from_devices(self, systorageElid):
        url = f'/entity/storageSystem/{systorageElid}/DevicesAll'
        body = {"relationRestrictions": {"role": {"value": "controller", "operator": "="}},
                "entityRestrictions": {}, "returnRelationAttributes": [], "returnEntityAttributes": []}
        res = self.req(url, body)
        return res if res else []

    def get_volumes_from_storage(self, storageElid):
        url = f'/entity/storage/{storageElid}/StorageUnitVolumes'
        body = {"relationRestrictions": {}, "entityRestrictions": {},
                "returnRelationAttributes": [], "returnEntityAttributes": []}
        vols = self.req(url, body)
        data = []
        if not vols:
            return data
        for vol in vols:
            vol["entity"] = self.query_vol(vol["entity"]["elid"])
            data.append(vol)
        return data

    def get_fs_from_volume(self, volumeElid):
        url = '/entity/storageUnitVolume/' + volumeElid + '/FileSystems'
        body = {}
        fss = self.req(url, body)
        data = []
        if not fss:
            return data
        for fs in fss:
            fs["entity"] = self.query_fs(fs["entity"]["elid"])
            data.append(fs)
        return data

    def get_type_of_obj(self, elid):
        url=f'/entity/storageUnit/query'
        body={"restrictions": {"elid": {"value": elid,"operator": "="}}
            ,"returnAttributes": ["targetSubEntity"]}
        res = self.req(url, body)
        return res[0]["targetSubEntity"]
    
    def get_query_of_obj(self, elid):
        url=f'/entity/storageUnit/query'
        body={"restrictions": {"elid": {"value": elid,"operator": "="}}
            ,"returnAttributes": []}
        res = self.req(url, body)
        return res[0]
    
    def search_obj_visid_type(self, visid, type):
        url=f'/entity/storageUnit/query'
        body = { "restrictions": {"targetSubEntity": {"value": type, "operator": "="},
            "visibleId": {"value": visid, "operator": "="}}, "returnAttributes": ["elid"]}
        res = self.req(url, body)
        return res[0] if res != [] else None

    def get_fs_from_lun(self, lunElid):
        url = '/entity/storageUnitLun/' + lunElid + '/FileSystems'
        body = {}
        fss = self.req(url, body)
        data = []
        if not fss:
            return data
        for fs in fss:
            fs["entity"] = self.query_fs(fs["entity"]["elid"])
            data.append(fs)
        return data

    def get_luns_for_volume(self, volumeElid):
        url = '/entity/storageUnitVolume/' + volumeElid + '/StorageUnits'
        luns = self.req(url, self.consist_of_body)
        data = []
        if not luns:
            return data
        for lun in luns:
            if self.get_type_of_obj(lun["entity"]["elid"]) == "LUN":
                lun["entity"] = self.query_lun(lun["entity"]["elid"])
                data.append(lun)
        return data
    
    def get_qtrees_for_volume(self, volumeElid):
        url = '/entity/storageUnitVolume/' + volumeElid + '/StorageUnits'
        qtrees = self.req(url, self.consist_of_body)
        data = []
        if not qtrees:
            return data
        for qtree in qtrees:
            if self.get_type_of_obj(qtree["entity"]["elid"]) == "QTREE":
                qtree["entity"] = self.query_qtree(qtree["entity"]["elid"])
                data.append(qtree)
        return data

    def get_qtrees_for_lun(self, lunElid):
        url = '/entity/storageUnitLun/' + lunElid + '/StorageUnits'
        qtrees = self.req(url, self.consist_of_body)
        data = []
        if not qtrees:
            return data
        for qtree in qtrees:
            if self.get_type_of_obj(qtree["entity"]["elid"]) == "QTREE":
                qtree["entity"] = self.query_qtree(qtree["entity"]["elid"])
                data.append(qtree)
        return data

    def get_fs_from_qtree(self, qtreeElid):
        url = '/entity/storageUnitQtree/' + qtreeElid + '/FileSystems'
        body = {}
        fss = self.req(url, body)
        data = []
        if not fss:
            return data
        for fs in fss:
            fs["entity"] = self.query_fs(fs["entity"]["elid"])
            data.append(fs)
        return data

    def getDiskArrayBySSElid(self,ssElid):
        url = '/entity/storageSystem/'+ssElid+'/DevicesAll'
        body={ "relationRestrictions": { "role": { "value": "disk_array", "operator": "=" } }, "entityRestrictions": {}, "returnRelationAttributes": [], "returnEntityAttributes": [] }
        ret=self.req(url, body)
        elid=[]
        for i in range(len(ret)):
            elid.append({'elid':ret[i]['entity']['elid'],'visibleId':ret[i]['entity']['visibleId']})
            url1 = '/entity/storage/query'
            body1={"restrictions": {"elid":{"value": ret[i]['entity']['elid'],"operator": "="}}}
            ret1=self.req(url1, body1)
            if ret1[0]['cIdMetka']:
                elid[i]['cIdMetka'] = ret1[0]['cIdMetka']
            else:
                elid[i]['cIdMetka'] = ""
        return elid

    def get_hard_drives_from_storage(self,storageElid):
        url=f'/entity/storage/{storageElid}/HardDisks'
        body={"relationRestrictions":{},"entityRestrictions":{},
              "returnEntityAttributes":["elid","serialNo"],
              "returnRelationAttributes":["linkElid","slot"]}
        ret=self.req(url, body)
        drives={}
        for drive in ret:
            drives[drive['entity']['serialNo']]={"linkElid":drive['relation']['linkElid'],
                                                 "slotName":drive['relation']['slot'],
                                                 "elid":drive['entity']['elid']}
        return drives

    def get_hard_drives(self):
        url='/entity/hardDisk/query'
        body={"restrictions":{"serialNo":{"operator":"like","value":"*"}},"returnAttributes":["elid","serialNo"]}
        ret=self.req(url, body)
        drives={}
        for drive in ret:
            drives[drive['serialNo']]={"elid":drive['elid']}
        return drives
    
    def create_hard_drive(self,hddName,SN,manufacturer,size,model,type,intType,driveStatus):
        url='/entity/hardDisk/create'
        driveStatus='installed' if driveStatus==1 else 'unknown'
        size=size.replace(',','.')
        sizeF=float(size)
        sizeF='{:.15f}'.format(sizeF)
        if intType == "":
            tt=type
        else:
            tt=type+" "+intType
        body={
            "cPartNumber": model,
            "capacity": sizeF,
            "lifecycleStatus": driveStatus,
            "lifecycleStatusDate": datetime.utcnow().isoformat() + "Z",
            "manufacturer": manufacturer,
            "serialNo": SN,
            "type": tt,
            "visibleId": hddName
        }

        ret=self.req(url, body)

        return ret
    
    def link_hard_drive_to_storage(self,storageElid,driveElid,slotName):
        url = f'/entity/storage/{storageElid}/update'
        body={"createLinkHardDisk":[{"linkedElid":driveElid,"slot":str(slotName)}]}
        ret=self.req(url, body)
        if not ret:
            linkElid = None
        else:
            linkElid=ret['createLinkHardDisk'][0]['linkElid']
        return linkElid
    
    def unlink_hard_drive_from_storage(self,storageElid,linkElid):
        url = f'/entity/storage/{storageElid}/update'
        body={"deleteLinkHardDisk":[{"linkElid":linkElid}]}
        ret=self.req(url, body)
    
    def create_volume(self, body):
        print(1)
        url='/entity/storageUnitVolume/create'
        ret=self.req(url, body)
        return ret["elid"]

    def update_volume(self,volElid,body):
        url = f'/entity/storageUnitVolume/{volElid}/update'
        ret=self.req(url, body)

    def link_volume_to_storage(self,storageElid,volumeElid):
        url = f'/entity/storage/{storageElid}/update'
        body={"createLinkStorageUnitVolume":[{"linkedElid":volumeElid}]}
        ret=self.req(url, body)
        linkElid=ret['createLinkStorageUnitVolume'][0]['linkElid']
        return linkElid if linkElid else None
    
    def unlink_volume_to_storage(self,storageElid,linkElid):
        url = f'/entity/storage/{storageElid}/update'
        body={"deleteLinkStorageUnitVolume":[{"linkElid":linkElid}]}
        ret=self.req(url, body)

    def delete_volume(self, volumeElid):
        url = f'/entity/storageUnitVolume/{volumeElid}/delete'
        body={}
        ret=self.req(url, body)
        return ret

    def create_fs(self, body):
        print(2)
        url = f'/entity/fileSystem/create'
        ret = self.req(url, body)
        return ret["elid"]
    
    def update_fs(self,fsElid,body):
        url = f'/entity/fileSystem/{fsElid}/update'
        ret=self.req(url, body)
    
    def link_fs_to_unit(self, unitElid, fsElid):
        url = f'/entity/fileSystem/{fsElid}/update'
        body={"createLinkStorageUnit":[{"linkedElid":unitElid}]}
        ret=self.req(url, body)
        linkElid=ret['createLinkStorageUnit'][0]['linkElid']
        return linkElid if linkElid else None
    
    def unlink_fs_to_unit(self, linkElid, fsElid):
        url = f'/entity/fileSystem/{fsElid}/update'
        body={"deleteLinkStorageUnit":[{"linkElid":linkElid}]}
        ret=self.req(url, body)
        return ret

    def delete_fs(self, fsElid):
        url = f'/entity/fileSystem/{fsElid}/delete'
        body={}
        ret=self.req(url, body)
        return ret

    def create_lun(self, body):
        print(3)
        url = f'/entity/storageUnitLun/create'
        ret = self.req(url, body)
        return ret["elid"]

    def update_lun(self,lunElid,body):
        url = f'/entity/storageUnitLun/{lunElid}/update'
        ret=self.req(url, body)
    
    def link_lun_to_unit(self, unitElid, lunElid):
        url = f'/entity/storageUnitLun/{lunElid}/update'
        body={"createLinkStorageUnit":[{"linkedElid":unitElid, "role": "BUILT_FROM"}]}
        ret=self.req(url, body)
        linkElid=ret['createLinkStorageUnit'][0]['linkElid']
        return linkElid if linkElid else None
    
    def unlink_lun_to_unit(self, linkElid, lunElid):
        url = f'/entity/storageUnitLun/{lunElid}/update'
        body={"deleteLinkStorageUnit":[{"linkElid":linkElid}]}
        ret=self.req(url, body)

    def delete_lun(self, lunElid):
        url = f'/entity/storageUnitLun/{lunElid}/delete'
        body={}
        ret=self.req(url, body)
        return ret

    def create_qtree(self, body):
        print(4)
        url = f'/entity/storageUnitQtree/create'
        ret = self.req(url, body)
        return ret["elid"]

    def update_qtree(self,qtreeElid,body):
        # print(qtreeElid)
        url = f'/entity/storageUnitQtree/{qtreeElid}/update'
        ret=self.req(url, body)
        # if qtreeElid == "XO9GM1QOY1QZKA":
        #     print(body)
        #     print(ret)

    def link_qtree_to_unit(self, unitElid, qtreeElid):
        url = f'/entity/storageUnitQtree/{qtreeElid}/update'
        body={"createLinkStorageUnit":[{"linkedElid":unitElid, "role": "BUILT_FROM"}]}
        ret=self.req(url, body)
        linkElid=ret['createLinkStorageUnit'][0]['linkElid']
        return linkElid if linkElid else None
    
    def unlink_qtree_to_unit(self, linkElid, qtreeElid):
        url = f'/entity/storageUnitQtree/{qtreeElid}/update'
        body={"deleteLinkStorageUnit":[{"linkElid":linkElid}]}
        ret=self.req(url, body)

    def delete_qtree(self, qtreeElid):
        url = f'/entity/storageUnitQtree/{qtreeElid}/delete'
        body={}
        ret=self.req(url, body)
        return ret
    
    def query_qtree(self, elid):
        url ='/entity/storageUnitQtree/query'
        body = { "restrictions": { "elid": { "value": elid,
                "operator": "="}} , "returnAttributes": []}
        ret = self.req(url, body)
        return ret[0] if ret != [] else []
    
    def query_lun(self, elid):
        url ='/entity/storageUnitLun/query'
        body = { "restrictions": { "elid": { "value": elid,
                "operator": "="}} , "returnAttributes": []}
        ret = self.req(url, body)
        return ret[0] if ret != [] else []
    
    def query_vol(self, elid):
        url ='/entity/storageUnitVolume/query'
        body = { "restrictions": { "elid": { "value": elid,
                "operator": "="}} , "returnAttributes": []}
        ret = self.req(url, body)
        return ret[0] if ret != [] else []
    
    def query_fs(self, elid):
        url ='/entity/fileSystem/query'
        body = { "restrictions": { "elid": { "value": elid,
                "operator": "="}} , "returnAttributes": []}
        ret = self.req(url, body)
        return ret[0] if ret != [] else []
    
    def query_visid_vol(self, visid):
        url ='/entity/storageUnitVolume/query'
        body = { "restrictions": { "visibleId": { "value": visid,
                "operator": "="}} , "returnAttributes": ["elid"]}
        ret = self.req(url, body)
        return ret[0]["elid"] if ret != [] else None
    
    def query_visid_lun(self, visid):
        url ='/entity/storageUnitLun/query'
        body = { "restrictions": { "visibleId": { "value": visid,
                "operator": "="}} , "returnAttributes": ["elid"]}
        ret = self.req(url, body)
        return ret[0]["elid"] if ret != [] else None
    
    def query_visid_qtree(self, visid):
        url ='/entity/storageUnitQtree/query'
        body = { "restrictions": { "visibleId": { "value": visid,
                "operator": "="}} , "returnAttributes": ["elid"]}
        ret = self.req(url, body)
        return ret[0]["elid"] if ret != [] else None
    
    def query_visid_fs(self, visid):
        url ='/entity/fileSystem/query'
        body = { "restrictions": { "visibleId": { "value": visid,
                "operator": "="}} , "returnAttributes": ["elid"]}
        ret = self.req(url, body)
        return ret[0]["elid"] if ret != [] else None
    
    def link_unit_to_sys(self, sysElid, unitElid):
        url = f'/entity/storageSystem/{sysElid}/update'
        body = {"createLinkStorageUnit": [{"linkedElid": unitElid}]}
        ret = self.req(url, body)
        return ret['createLinkStorageUnit'][0]['linkElid'] if ret else None

    def get_units_from_sys(self, sysElid):
        url = f'/entity/storageSystem/{sysElid}/StorageUnits'
        body = {"relationRestrictions": {},"entityRestrictions": {},
                "returnRelationAttributes": [],"returnEntityAttributes": []}
        ret = self.req(url, body)
        return ret if ret else []
    
    def unlink_unit_from_sys(self, sysElid, linkId):
        url = f'/entity/storageSystem/{sysElid}/update'
        body = {"deleteLinkStorageUnit": [{"linkElid": linkId}]}
        ret = self.req(url, body)
        return ret