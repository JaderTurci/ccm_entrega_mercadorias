# sql.py

import pyodbc #conexao SQL
import os

#
# SQL SERVER
#

class Sql: 
	def __init__(self):
		self.conn_sql = None
		self.debug = False

	def connect(self, database="mafraInd"):
		if self.debug:
			print("Conectando SQL...")

		# Lê username e senha do arquivo config.local
		user, pwd = None, None
		if os.path.exists("config.local"):
			with open("config.local", "r") as f:
				lines = f.read().splitlines()
				if len(lines) >= 2:
					user = lines[0].strip()
					pwd = lines[1].strip()
		if not user or not pwd:
			raise Exception("Arquivo config.local inválido ou não encontrado")

		self.conn_sql = pyodbc.connect(
			'DRIVER={ODBC Driver 17 for SQL Server};' +
			'SERVER=10.11.10.25;' +
			'DATABASE=' + database + ';' +
			'UID=' + user + ';' +
			'PWD=' + pwd)

		if self.debug:
			print("OK")

	def open(self, cmd, msg=""):
		cursor = self.conn_sql.cursor()

		#if msg>"":
		#	msg = msg.replace("'","`")
		#	cmd2 = f"""
		#		set nocount on;
		#		insert into ccm_log..log_geral(historico) select '{msg}' 
		#		select @@IDENTITY as id_log_geral
		#		"""
		#	rows_log = self.open(cmd2)
		#	r_log = rows_log[0]
		
		if self.debug:
			print(cmd)
		cursor.execute(cmd)
		rows=cursor.fetchall()
		self.conn_sql.commit()

		#if msg>"":
		#	cmd2 = f"update gaia_log..log_geral set data_fim=getdate() where id_log_geral = {r_log.id_log_geral} "
		#	self.exec(cmd2)        

		return rows

	def exec(self, cmd, msg=""):
		cursor = self.conn_sql.cursor()

		#if msg>"":
	    #	msg = msg.replace("'","`")
		#	cmd2 = f"""
	    #		set nocount on;
		#		insert into ccm_log..log_geral(historico) select '{msg}' 
		#		select @@IDENTITY as id_log_geral
		#		"""
		#	rows_log = self.open(cmd2)
		#	r_log = rows_log[0]

		if self.debug:
			print(cmd)
		cursor.execute(cmd)
		self.conn_sql.commit()

		#if msg>"":
		#	cmd2 = f"update gaia_log..log_geral set data_fim=getdate() where id_log_geral = {r_log.id_log_geral} "
		#	self.exec(cmd2)        

