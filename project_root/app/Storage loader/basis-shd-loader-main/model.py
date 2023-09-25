#!/usr/bin/python3

class DriveList:
    def __init__(self,dcimDrives,monitoringDrives) -> None:
        self.dcimDrives=dcimDrives
        self.monitoringDrives=monitoringDrives
        
    def DrivesForCreate(self):
        if self.dcimDrives and self.monitoringDrives:
            newDrivesKeys=self.monitoringDrives.keys()-self.dcimDrives.keys()
            return newDrivesKeys if newDrivesKeys else None
    
    def DrivesForDelete(self):
        if self.dcimDrives and self.monitoringDrives:
            deleteDrivesKeys=self.dcimDrives.keys()-self.monitoringDrives.keys()
            return deleteDrivesKeys if deleteDrivesKeys else None


class VolumeList:
    def __init__(self,dcimVolumes,monitoringVolumes) -> None:
        self.dcimVolumes=dcimVolumes
        self.monitoringVolumes=monitoringVolumes
        
    def VolumesForCreate(self):
        if self.dcimVolumes and self.monitoringVolumes:
            newVolumesKeys=self.monitoringVolumes.keys()-self.dcimVolumes.keys()
            return newVolumesKeys if newVolumesKeys else None
    
    def VolumesForDelete(self):
        if self.dcimVolumes and self.monitoringVolumes:
            deleteVolumesKeys=self.dcimVolumes.keys()-self.monitoringVolumes.keys()
            return deleteVolumesKeys if deleteVolumesKeys else None

class Drive:
    def __init__(self,SN) -> None:
        self.sn=SN
        self.isHasDataFromDcim=False
        
    def import_elid_data_from_dcim(self,elid):
        self.elid=elid

    def import_data_from_monitoring(self,vendor,size,status,model,slot,type):
        self.isHasDataFromDcim=True
        self.vendor=vendor
        self.size=size
        self.status=status
        self.model=model
        self.slot=slot
        self.type=type

class Storage:
    def __init__(self,visibleID="",dcimElid="",cIdMetka="",SN="") -> None:
        self.sn=SN
        self.visibleID=visibleID
        self.dcimElid=dcimElid
        self.cIdMetka=cIdMetka
        self.dcimDrives={}
        self.monitoringDrives={}
        self.dcimVolumes={}
        self.monitoringVolumes={}
        self.dcimLUNs=[]

        
    def import_elid_data_from_dcim(self,elid):
        self.elid=elid
    
    def import_drive_from_monitoring(self,drives):
        self.monitoringDrives=drives
        
    def import_volume_from_monitoring(self,volumes):
        self.monitoringVolumes=volumes
        
    def import_drive_from_dcim(self,drives):
        self.dcimDrives=drives

    def import_volume_from_dcim(self,volumes):
        self.dcimVolumes=volumes
    
    def DrivesForLink(self):
        if self.dcimDrives and self.monitoringDrives:
            newDrivesKeys=self.monitoringDrives.keys()-self.dcimDrives.keys()
            drives={}
            for drive in newDrivesKeys:
                drives[drive]=self.monitoringDrives[drive]
            return drives if drives else None
    
    def DrivesForUnlink(self):
        if self.dcimDrives and self.monitoringDrives:
            deleteDrivesKeys=self.dcimDrives.keys()-self.monitoringDrives.keys()
            drives={}
            for drive in deleteDrivesKeys:
                drives[drive]=self.dcimDrives[drive]
            return drives if drives else None
        
        
    def VolumesForLink(self):
        if self.dcimVolumes and self.monitoringVolumes:
            newVolumesKeys=self.monitoringVolumes.keys()-self.dcimVolumes.keys()
            volumes={}
            for volume in newVolumesKeys:
                volumes[volume]=self.monitoringVolumes[volume]
            return volumes if volumes else None
    
    def VolumesForUnlink(self):
        if self.dcimVolumes and self.monitoringVolumes:
            deleteVolumesKeys=self.dcimVolumes.keys()-self.monitoringVolumes.keys()
            volumes={}
            for volume in deleteVolumesKeys:
                volumes[volume]=self.dcimVolumes[volume]
            return volumes if volumes else None